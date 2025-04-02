<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;

class Show extends Component
{
    public Contract $contract;
    public $renewals = [];
    public $ancestors = [];
    public $media = [];

    public function mount(Contract $contract)
    {
        $this->contract = $contract->load(['tenant', 'property', 'renewals.tenant', 'renewals.property', 'previousContract']);

        // Get renewals and ancestors
        $this->renewals = $contract->allRenewals();
        $this->ancestors = $contract->allAncestors();

        // Load media
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
                'url' => route('media.show', $item->id),
                'download_url' => route('media.download', $item->id),
            ];
        })->toArray();
    }

    public function terminateContract()
    {
        // Terminate the contract
        $this->contract->update(['validity' => 'NO']);

        // Update the property's status to 'VACANT'
        $this->contract->property->update(['status' => 'VACANT']);

        // Reload the contract
        $this->contract = $this->contract->fresh(['tenant', 'property']);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract terminated successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.contracts.show');
    }
}
