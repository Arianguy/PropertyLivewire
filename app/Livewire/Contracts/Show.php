<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Receipt;
use Livewire\Component;
use Illuminate\Support\Collection;

class Show extends Component
{
    public Contract $contract;
    public $media = [];
    public $previousContracts = [];
    public $renewalContracts = [];

    public float $totalRentScheduled = 0;
    public float $totalRentCleared = 0;
    public float $totalRentPendingClearance = 0;
    public float $balanceDue = 0;

    public function mount(Contract $contract)
    {
        $this->contract = $contract->load(['receipts' => function ($query) {
            $query->select('id', 'contract_id', 'receipt_category', 'amount', 'status', 'payment_type', 'cheque_no', 'narration', 'receipt_date');
        }]);
        $this->loadMedia();
        $this->loadContractHistory();
        $this->calculateRentTotals();
    }

    public function calculateRentTotals()
    {
        $allReceipts = $this->contract->receipts ?? collect();
        $rentReceipts = $allReceipts->where('receipt_category', 'RENT');

        // 1. Collection Scheduled: Sum of RENT category receipts
        $this->totalRentScheduled = $rentReceipts->sum('amount');

        // 2. Unscheduled: Contract Amount - Collection Scheduled
        $this->balanceDue = max(0, $this->contract->amount - $this->totalRentScheduled);

        // 3. Realized Amount: Sum of CLEARED RENT receipts + RETURN CHEQUE receipts
        $clearedRent = $rentReceipts->where('status', 'CLEARED')->sum('amount');
        $returnChequePayments = $allReceipts->where('receipt_category', 'RETURN CHEQUE')->sum('amount'); // Assuming RETURN CHEQUE category implies realized funds
        $this->totalRentCleared = $clearedRent + $returnChequePayments;

        // 4. Balance Pending Realization: Total Contract Amount - Realized Amount
        $this->totalRentPendingClearance = max(0, $this->contract->amount - $this->totalRentCleared);
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
