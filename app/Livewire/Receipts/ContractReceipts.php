<?php

namespace App\Livewire\Receipts;

use App\Models\Contract;
use App\Models\Receipt;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use App\Models\BouncedReceipt;
use App\Models\BouncedStatus;

#[Title('Contract Receipts')]
class ContractReceipts extends Component
{
    use WithPagination;

    public Contract $contract;
    public string $sortField = 'receipt_date';
    public string $sortDirection = 'asc';

    #[Url]
    public string $search = '';

    public bool $showDeleteModal = false;
    public ?Receipt $receiptToDelete = null;

    public function mount(Contract|int $contract)
    {
        if (is_int($contract)) {
            $this->contract = Contract::findOrFail($contract);
        } else {
            $this->contract = $contract;
        }
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function deleteReceipt(Receipt $receipt)
    {
        $this->receiptToDelete = $receipt;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->receiptToDelete) {
            $this->receiptToDelete->delete();
            $this->receiptToDelete = null;
            $this->showDeleteModal = false;
            $this->dispatch('receipt-deleted');
            session()->flash('message', 'Receipt deleted successfully.');
        }
    }

    public function cancelDelete()
    {
        $this->receiptToDelete = null;
        $this->showDeleteModal = false;
    }

    #[Computed]
    public function receipts()
    {
        $query = $this->contract->receipts()
            ->with(['resolvedReceipt', 'resolutionReceipts'])
            ->withSum('resolutionReceipts', 'amount')
            ->when($this->search, function ($query) {
                $query->where('narration', 'like', '%' . $this->search . '%')
                    ->orWhere('cheque_no', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_type', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            });

        if ($this->sortField === 'receipt_date') {
            $query->orderByRaw(
                "CASE
                    WHEN payment_type = 'CHEQUE' AND deposit_date IS NOT NULL THEN deposit_date
                    WHEN payment_type = 'CHEQUE' AND deposit_date IS NULL THEN cheque_date
                    ELSE receipt_date
                 END {$this->sortDirection}"
            );
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        if ($this->sortField !== 'status') {
            $query->orderBy('status', 'asc');
        }
        $query->orderBy('id', 'desc');

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.receipts.contract-receipts', [
            'receipts' => $this->receipts,
        ]);
    }
}
