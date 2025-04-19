<?php

namespace App\Livewire\Receipts;

use App\Models\Contract;
use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class ContractReceipts extends Component
{
    use WithPagination;

    public $contract;
    public $sortField = 'receipt_category';
    public $sortDirection = 'asc';

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $receiptsQuery = Receipt::where('contract_id', $this->contract->id)
            ->with(['resolutionReceipts'])
            ->withSum('resolutionReceipts', 'amount');

        if ($this->sortField === 'date') {
            $receiptsQuery->orderBy('receipt_date', $this->sortDirection);
        } else {
            $receiptsQuery->orderBy($this->sortField, $this->sortDirection);
        }

        $receiptsQuery->orderBy('status', 'asc');

        $receipts = $receiptsQuery->paginate(10);

        return view('livewire.receipts.contract-receipts', [
            'receipts' => $receipts
        ]);
    }
}
