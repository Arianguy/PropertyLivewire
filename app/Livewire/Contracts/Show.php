<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;

class Show extends Component
{
    public Contract $contract;
    public $media = [];
    public $previousContracts = [];
    public $renewalContracts = [];

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
        $this->loadMedia();
        $this->loadContractHistory();
    }

    public function loadMedia()
    {
        $this->media = $this->contract->getMedia('contracts_copy')->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->file_name,
                'size' => $item->size,
                'type' => $item->mime_type,
                'url' => $item->getUrl(),
                'download_url' => $item->getUrl() . '/download',
                'thumbnail' => $item->hasGeneratedConversion('thumb')
                    ? $item->getUrl('thumb')
                    : null
            ];
        })->toArray();
    }

    public function loadContractHistory()
    {
        // Get all previous contracts (ancestors)
        $this->previousContracts = $this->contract->allAncestors()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'name' => $contract->name,
                    'tenant' => $contract->tenant->name,
                    'property' => $contract->property->name,
                    'start_date' => $contract->cstart->format('M d, Y'),
                    'end_date' => $contract->cend->format('M d, Y'),
                    'amount' => number_format($contract->amount, 2),
                    'security_deposit' => number_format($contract->sec_amt, 2),
                    'ejari' => $contract->ejari,
                    'type' => $contract->type,
                    'validity' => $contract->validity,
                ];
            })->toArray();

        // Get all renewal contracts
        $this->renewalContracts = $this->contract->allRenewals()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'name' => $contract->name,
                    'tenant' => $contract->tenant->name,
                    'property' => $contract->property->name,
                    'start_date' => $contract->cstart->format('M d, Y'),
                    'end_date' => $contract->cend->format('M d, Y'),
                    'amount' => number_format($contract->amount, 2),
                    'security_deposit' => number_format($contract->sec_amt, 2),
                    'ejari' => $contract->ejari,
                    'type' => $contract->type,
                    'validity' => $contract->validity,
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
