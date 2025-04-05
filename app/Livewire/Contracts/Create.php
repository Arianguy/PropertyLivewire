<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use WithFileUploads;

    public $tenant_id;
    public $property_id;
    public $cstart;
    public $cend;
    public $amount;
    public $sec_amt;
    public $ejari;
    public $name;
    public $cont_copy;
    public $media = [];

    protected function rules()
    {
        return [
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'cstart' => 'required|date',
            'cend' => 'required|date|after:cstart',
            'amount' => 'required|numeric|min:0',
            'sec_amt' => 'required|numeric|min:0',
            'ejari' => 'nullable|string',
            'cont_copy.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }

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
        $this->validate();

        try {
            DB::beginTransaction();

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
                'validity' => 'YES',
                'type' => 'original'
            ]);

            // Update property status to LEASED
            Property::find($this->property_id)->update(['status' => 'LEASED']);

            // Handle multiple file uploads
            if ($this->cont_copy) {
                foreach ($this->cont_copy as $file) {
                    $contract->addMedia($file->getRealPath())
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('contracts_copy');
                }
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Contract created successfully!'
            ]);

            return redirect()->route('contracts.table');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error creating contract: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contracts.create', [
            'tenants' => Tenant::all(),
            'properties' => Property::where('status', 'VACANT')->get(),
        ]);
    }
}
