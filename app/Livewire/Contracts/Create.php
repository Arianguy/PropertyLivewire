<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public $tenant_id;
    public $property_id;
    public $cstart;
    public $cend;
    public $amount;
    public $sec_amt;
    public $ejari = 'YES';
    public $validity = 'YES';
    public $name;
    public $cont_copy = [];

    public function mount()
    {
        $this->name = $this->generateUniqueRandomName();
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
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'cstart' => 'required|date|before:cend',
            'cend' => 'required|date|after:cstart',
            'amount' => 'required|numeric|min:0',
            'sec_amt' => 'required|numeric|min:0',
            'ejari' => 'required|string',
            'validity' => 'required|string',
            'cont_copy.*' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'tenant_id.required' => 'Please select a tenant.',
            'property_id.required' => 'Please select a property.',
            'cstart.before' => 'The contract start date must be before the end date.',
            'cend.after' => 'The contract end date must be after the start date.',
            'cont_copy.*.mimes' => 'Contract documents must be PDF, JPG, JPEG, or PNG files.',
            'cont_copy.*.max' => 'Contract documents must be less than 10MB.',
        ]);

        // Create the contract
        $contract = Contract::create([
            'name' => $this->name,
            'tenant_id' => $this->tenant_id,
            'property_id' => $this->property_id,
            'cstart' => $this->cstart,
            'cend' => $this->cend,
            'amount' => $this->amount,
            'sec_amt' => $this->sec_amt,
            'ejari' => $this->ejari,
            'validity' => $this->validity,
            'type' => 'original'
        ]);

        // Update property status to LEASED
        Property::find($this->property_id)->update(['status' => 'LEASED']);

        // Handle file uploads
        if (count($this->cont_copy) > 0) {
            foreach ($this->cont_copy as $file) {
                $contract->addMedia($file->getRealPath())
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('contracts_copy');
            }
        }

        // Notify the user
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract created successfully!'
        ]);

        // Redirect to the contracts table
        return redirect()->route('contracts.table');
    }

    public function render()
    {
        return view('livewire.contracts.create', [
            'tenants' => Tenant::orderBy('name')->get(),
            'properties' => Property::where('status', 'VACANT')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
