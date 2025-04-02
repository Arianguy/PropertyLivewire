<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class Renew extends Component
{
    use WithFileUploads;

    public Contract $previousContract;
    public $name;
    public $tenant_id;
    public $property_id;
    public $cstart;
    public $cend;
    public $amount;
    public $sec_amt;
    public $ejari = 'YES';
    public $validity = 'YES';
    public $cont_copy = [];

    public function mount(Contract $contract)
    {
        // Ensure the contract is valid for renewal
        if ($contract->validity !== 'YES' || $contract->renewals()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'This contract is not eligible for renewal.'
            ]);

            return redirect()->route('contracts.table');
        }

        $this->previousContract = $contract;

        // Generate a unique contract name
        $this->name = $this->generateUniqueRandomName();

        // Set values from the previous contract
        $this->tenant_id = $contract->tenant_id;
        $this->property_id = $contract->property_id;

        // Calculate suggested new dates
        $this->cstart = Carbon::parse($contract->cend)->addDay()->format('Y-m-d');
        $this->cend = Carbon::parse($this->cstart)->addYear()->subDay()->format('Y-m-d');

        // Set the same amounts from the previous contract
        $this->amount = $contract->amount;
        $this->sec_amt = $contract->sec_amt;
    }

    public function generateUniqueRandomName($length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $randomName = '';
        $attempts = 0;
        $maxAttempts = 10;

        do {
            $randomName = '';
            for ($i = 0; $i < $length; $i++) {
                $randomName .= $characters[rand(0, strlen($characters) - 1)];
            }
            $attempts++;
        } while (Contract::where('name', $randomName)->exists() && $attempts < $maxAttempts);

        if ($attempts === $maxAttempts) {
            throw new \Exception('Unable to generate a unique random name after multiple attempts.');
        }

        return $randomName;
    }

    public function save()
    {
        $validated = $this->validate([
            'cstart' => 'required|date|after:' . $this->previousContract->cend,
            'cend' => 'required|date|after:cstart',
            'amount' => 'required|numeric|min:0',
            'sec_amt' => 'required|numeric|min:0',
            'ejari' => 'required|string',
            'validity' => 'required|string',
            'cont_copy.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'cstart.after' => 'The contract start date must be after the previous contract end date.',
            'cend.after' => 'The contract end date must be after the start date.',
            'cont_copy.*.mimes' => 'Contract documents must be PDF, JPG, JPEG, or PNG files.',
            'cont_copy.*.max' => 'Contract documents must be less than 10MB.',
        ]);

        // Create the new contract for renewal
        $newContract = Contract::create([
            'name' => $this->name,
            'tenant_id' => $this->tenant_id,
            'property_id' => $this->property_id,
            'cstart' => $this->cstart,
            'cend' => $this->cend,
            'amount' => $this->amount,
            'sec_amt' => $this->sec_amt,
            'ejari' => $this->ejari,
            'validity' => $this->validity,
            'type' => 'renewed',
            'previous_contract_id' => $this->previousContract->id,
        ]);

        // Set the validity of the previous contract to 'NO'
        $this->previousContract->update(['validity' => 'NO']);

        // Update property status to LEASED
        $newContract->property->update(['status' => 'LEASED']);

        // Handle file uploads
        if (count($this->cont_copy) > 0) {
            foreach ($this->cont_copy as $file) {
                $newContract->addMedia($file->getRealPath())
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('contracts_copy');
            }
        }

        // Notify the user
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract renewed successfully!'
        ]);

        // Redirect to the new contract details
        return redirect()->route('contracts.show', $newContract->id);
    }

    public function render()
    {
        return view('livewire.contracts.renew');
    }
}
