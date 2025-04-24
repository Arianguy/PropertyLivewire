<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class ResolveBouncedReceipt extends Component
{
    public $showModal = false;
    public $bouncedReceiptId;
    public $bouncedReceipt;
    public $contractId;

    // Form fields for the new receipt
    public $payment_type = 'CASH'; // Default to CASH
    public $amount;
    public $receipt_date;
    public $narration;
    public $transaction_reference; // For online transfer

    protected function rules()
    {
        $rules = [
            'payment_type' => 'required|in:CASH,ONLINE_TRANSFER',
            'amount' => 'required|numeric|min:0.01',
            'receipt_date' => 'required|date',
            'narration' => 'required|string|max:255',
        ];

        if ($this->payment_type === 'ONLINE_TRANSFER') {
            $rules['transaction_reference'] = 'required|string|max:100';
        }

        return $rules;
    }

    #[On('openResolveModal')]
    public function openModal($receiptId)
    {
        Log::info("Opening resolve modal for Receipt ID: {$receiptId}");
        $this->resetValidation();
        $this->resetForm();
        $this->bouncedReceiptId = $receiptId;
        // Eager load resolution receipts relationship and sum the amount
        $this->bouncedReceipt = Receipt::with('contract', 'resolutionReceipts')->find($this->bouncedReceiptId);

        if (!$this->bouncedReceipt || $this->bouncedReceipt->status !== 'BOUNCED') {
            Log::warning('Attempted to resolve non-bounced receipt', ['receipt_id' => $receiptId]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'This receipt is not bounced.']);
            return;
        }

        // Calculate remaining amount
        $totalResolved = $this->bouncedReceipt->resolutionReceipts->sum('amount');
        $remainingAmount = $this->bouncedReceipt->amount - $totalResolved;

        if ($remainingAmount <= 0) {
            Log::warning('Attempted to resolve already fully resolved receipt', ['receipt_id' => $receiptId]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'This bounced receipt has already been fully resolved.']);
            return;
        }

        $this->contractId = $this->bouncedReceipt->contract_id;
        $this->amount = $remainingAmount; // Pre-fill with remaining amount
        $this->receipt_date = now()->format('Y-m-d');
        $this->narration = 'Payment for bounced cheque #' . $this->bouncedReceipt->cheque_no . ' (Installment)';
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->payment_type = 'CASH';
        $this->amount = null;
        $this->receipt_date = null;
        $this->narration = null;
        $this->transaction_reference = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->bouncedReceipt = null;
        $this->bouncedReceiptId = null;
    }

    public function saveResolution()
    {
        $validated = $this->validate();

        if (!$this->bouncedReceipt) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Bounced receipt not found.']);
            return;
        }

        try {
            DB::beginTransaction();

            $newReceipt = Receipt::create([
                'contract_id' => $this->contractId,
                'receipt_category' => 'RETURN CHEQUE', // Set category to RETURN CHEQUE
                'payment_type' => $validated['payment_type'],
                'amount' => $validated['amount'],
                'receipt_date' => $validated['receipt_date'],
                'narration' => $validated['narration'],
                'transaction_reference' => $validated['payment_type'] === 'ONLINE_TRANSFER' ? $validated['transaction_reference'] : null,
                'status' => 'CLEARED', // Automatically cleared
                'deposit_date' => $validated['receipt_date'], // Set deposit date
                'remarks' => $validated['narration'], // Copy narration to remarks
                'resolves_receipt_id' => $this->bouncedReceiptId, // Link to the bounced receipt
            ]);

            // Optionally add transfer receipt image if needed in future
            // if ($this->payment_type === 'ONLINE_TRANSFER' && $this->transfer_receipt_image) {
            //     $newReceipt->addMedia(...)->toMediaCollection('transfer_receipts');
            // }

            DB::commit();

            $this->closeModal();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Resolution payment recorded successfully.']);
            $this->dispatch('receiptsUpdated'); // Event to refresh tables

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving resolution receipt: ' . $e->getMessage());
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error saving resolution payment: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.receipts.resolve-bounced-receipt');
    }
}
