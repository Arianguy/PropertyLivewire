<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Contract $contract;
    public $tenant_id;
    public $property_id;
    public $cstart;
    public $cend;
    public $amount;
    public $sec_amt;
    public $ejari;
    public $validity;
    public $cont_copy = [];
    public $media = [];

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
        $this->tenant_id = $contract->tenant_id;
        $this->property_id = $contract->property_id;
        $this->cstart = $contract->cstart;
        $this->cend = $contract->cend;
        $this->amount = $contract->amount;
        $this->sec_amt = $contract->sec_amt;
        $this->ejari = $contract->ejari;
        $this->validity = $contract->validity;

        // Load existing media
        $this->loadMedia();
    }

    public function loadMedia()
    {
        $this->media = $this->contract->getMedia('contracts_copy')->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file_name' => $item->file_name,
                'size' => $item->size,
                'mime_type' => $item->mime_type,
                'url' => $item->getUrl(),
            ];
        })->toArray();
    }

    public function deleteMedia($mediaId)
    {
        $media = $this->contract->getMedia('contracts_copy')->where('id', $mediaId)->first();

        if ($media) {
            $media->delete();
            $this->loadMedia();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Document deleted successfully!'
            ]);
        }
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

        // Check if contract is valid for editing
        if ($this->contract->validity !== 'YES' && $this->contract->renewals()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You cannot edit a terminated or renewed contract.'
            ]);
            return;
        }

        // Update the contract
        $this->contract->update([
            'tenant_id' => $this->tenant_id,
            'property_id' => $this->property_id,
            'cstart' => $this->cstart,
            'cend' => $this->cend,
            'amount' => $this->amount,
            'sec_amt' => $this->sec_amt,
            'ejari' => $this->ejari,
            'validity' => $this->validity,
        ]);

        // Handle file uploads
        if (count($this->cont_copy) > 0) {
            foreach ($this->cont_copy as $file) {
                $this->contract->addMedia($file->getRealPath())
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('contracts_copy');
            }

            // Refresh media
            $this->loadMedia();
            $this->cont_copy = [];
        }

        // Notify the user
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract updated successfully!'
        ]);
    }

    public function render()
    {
        $originalPropertyId = $this->contract->property_id;

        return view('livewire.contracts.edit', [
            'tenants' => Tenant::orderBy('name')->get(),
            'properties' => Property::where(function ($query) use ($originalPropertyId) {
                $query->where('status', 'VACANT')
                    ->orWhere('id', $originalPropertyId);
            })
                ->orderBy('name')
                ->get(),
        ]);
    }
}
