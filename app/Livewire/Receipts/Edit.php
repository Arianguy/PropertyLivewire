<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use App\Models\Contract;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    use WithFileUploads;

    public $receipt;
    public $contract;
    public $receipt_category;
    public $payment_type;
    public $amount;
    public $receipt_date;
    public $narration;
    public $status;

    // VAT fields
    public $vat_rate;
    public $vat_amount;
    public $vat_inclusive;

    // Cheque fields
    public $cheque_no;
    public $cheque_bank;
    public $cheque_date;
    public $cheque_image;
    public $remove_cheque_image = false;

    // Transfer fields
    public $transaction_reference;
    public $transfer_receipt_image;
    public $remove_transfer_image = false;

    // Banks
    public $banks = [
        'ENBD' => 'Emirates NBD',
        'CBD' => 'Commercial Bank of Dubai',
        'FAB' => 'First Abu Dhabi Bank',
        'Mashreq Bank' => 'Mashreq Bank',
        'DIB' => 'Dubai Islamic Bank',
        'EIB' => 'Emirates Islamic Bank'
    ];

    // Debug properties
    public $debug_all_media;
    public $debug_specific_media;
    public $debug_media_url;
    public $debug_media_id;
    public $debug_collection_names;

    public $originalPaymentType;
    public $originalStatus;

    protected $listeners = [
        'refresh' => '$refresh',
        'runCashReceiptFix'
    ];

    public function mount(Receipt $receipt)
    {
        $this->receipt = $receipt;
        $this->contract = $receipt->contract;

        // Store original values
        $this->originalPaymentType = $receipt->payment_type;
        $this->originalStatus = $receipt->getRawStatus();

        // Fill the form with current values
        $this->receipt_category = $receipt->receipt_category;
        $this->payment_type = $receipt->payment_type;
        $this->amount = $receipt->amount;
        $this->receipt_date = $receipt->receipt_date->format('Y-m-d');
        $this->narration = $receipt->narration;
        $this->status = $receipt->getRawStatus();

        // VAT fields
        $this->vat_rate = $receipt->vat_rate ?? 0;
        $this->vat_amount = $receipt->vat_amount ?? 0;
        $this->vat_inclusive = $receipt->vat_inclusive ?? false;

        // Cheque fields
        $this->cheque_no = $receipt->cheque_no;
        $this->cheque_bank = $receipt->cheque_bank;
        $this->cheque_date = $receipt->cheque_date ? $receipt->cheque_date->format('Y-m-d') : null;

        // Transfer fields
        $this->transaction_reference = $receipt->transaction_reference;

        // Debug info
        $this->refreshDebugInfo();

        // Log mount info
        Log::info('Receipt Edit component mounted', [
            'receipt_id' => $receipt->id,
            'payment_type' => $receipt->payment_type,
            'has_cheque_image' => $receipt->hasChequeImage(),
            'has_transfer_image' => $receipt->hasTransferReceiptImage()
        ]);
    }

    public function calculateVat()
    {
        if ($this->receipt_category === 'VAT') {
            // For VAT category, reset VAT fields as entire amount is VAT
            $this->vat_rate = 0;
            $this->vat_amount = 0;
            $this->vat_inclusive = false;
        } elseif ($this->receipt_category === 'RENT' && $this->contract->isVatApplicable()) {
            // For RENT category, VAT is always inclusive
            $this->vat_inclusive = true;
            $this->vat_rate = $this->contract->getVatRate();
            
            if ($this->vat_inclusive) {
                // Calculate VAT from inclusive amount
                $this->vat_amount = ($this->amount * $this->vat_rate) / (100 + $this->vat_rate);
            } else {
                // Calculate VAT as additional to amount
                $this->vat_amount = ($this->amount * $this->vat_rate) / 100;
            }
        } else {
            // For other categories, reset VAT fields
            $this->vat_rate = 0;
            $this->vat_amount = 0;
            $this->vat_inclusive = false;
        }
    }

    public function updatedReceiptCategory()
    {
        $this->calculateVat();
    }

    public function updatedAmount()
    {
        $this->calculateVat();
    }

    public function refreshDebugInfo()
    {
        // Get debug info
        $allMedia = $this->receipt->getAllMedia();
        $specificMedia = $this->payment_type === 'CHEQUE'
            ? $this->receipt->getChequeMedia()
            : $this->receipt->getTransferMedia();

        $this->debug_all_media = count($allMedia);
        $this->debug_specific_media = count($specificMedia);
        $this->debug_media_id = $allMedia->first() ? $allMedia->first()->id : 'None';
        $this->debug_collection_names = $allMedia->pluck('collection_name')->join(', ') ?: 'None';

        // Get media URL
        if ($this->payment_type === 'CHEQUE') {
            $this->debug_media_url = $this->receipt->getFirstMediaUrl('cheque_images');
        } else {
            $this->debug_media_url = $this->receipt->getFirstMediaUrl('transfer_receipts');
        }
    }

    public function fixMedia()
    {
        // Only fix for non-cash payments
        if ($this->payment_type === 'CASH') {
            session()->flash('info', 'No attachments needed for cash payments.');
            return;
        }

        // Check if there are any media records
        $mediaCount = $this->receipt->media()->count();

        if ($mediaCount === 0) {
            session()->flash('error', 'No attachments found for this receipt.');
            return;
        }

        // Get the collection name based on payment type
        $collectionName = $this->payment_type === 'CHEQUE' ? 'cheque_images' : 'transfer_receipts';

        // Fix any media records with wrong collection name
        DB::table('media')
            ->where('model_type', Receipt::class)
            ->where('model_id', $this->receipt->id)
            ->update(['collection_name' => $collectionName]);

        // Refresh the receipt model and debug info
        $this->receipt->refresh();
        $this->refreshDebugInfo();

        session()->flash('success', "Media attachments fixed. Found {$mediaCount} media records.");
    }

    public function updatedPaymentType()
    {
        Log::info('Payment type changed to: ' . $this->payment_type);

        if ($this->payment_type === 'CASH') {
            $this->status = 'CLEARED';
            $this->cheque_no = null;
            $this->cheque_bank = null;
            $this->cheque_date = null;
            $this->transaction_reference = null;
            Log::info('Payment type is CASH, cleared fields and set status to CLEARED');
        } else {
            $this->status = 'PENDING';
            Log::info('Payment type is not CASH, set status to PENDING');
        }
    }

    public function rules()
    {
        $rules = [
            'receipt_category' => 'required|in:SECURITY_DEPOSIT,RENT,RETURN CHEQUE,VAT,CANCELLED',
            'payment_type' => 'required|in:CASH,CHEQUE,ONLINE_TRANSFER',
            'amount' => 'required|numeric|min:0.01',
            'receipt_date' => 'required|date',
            'narration' => 'required|string|max:255',
            'status' => 'required|in:PENDING,CLEARED,BOUNCED',
        ];

        // Add payment type specific validation
        if ($this->payment_type === 'CHEQUE') {
            $rules['cheque_no'] = 'required|string|max:50';
            $rules['cheque_bank'] = 'required|string';
            $rules['cheque_date'] = 'required|date';

            // Handle cheque image validation
            $hasExistingImage = count($this->receipt->getChequeMedia()) > 0;

            if (($this->receipt->payment_type !== 'CHEQUE' && !$hasExistingImage) ||
                ($this->receipt->payment_type === 'CHEQUE' && $hasExistingImage && $this->remove_cheque_image)
            ) {
                $rules['cheque_image'] = 'required|image|max:2048';
            } else {
                $rules['cheque_image'] = 'nullable|image|max:2048';
            }
        }

        if ($this->payment_type === 'ONLINE_TRANSFER') {
            $rules['transaction_reference'] = 'required|string|max:100';

            // Handle transfer image validation
            $hasExistingImage = count($this->receipt->getTransferMedia()) > 0;

            if (($this->receipt->payment_type !== 'ONLINE_TRANSFER' && !$hasExistingImage) ||
                ($this->receipt->payment_type === 'ONLINE_TRANSFER' && $hasExistingImage && $this->remove_transfer_image)
            ) {
                $rules['transfer_receipt_image'] = 'required|image|max:2048';
            } else {
                $rules['transfer_receipt_image'] = 'nullable|image|max:2048';
            }
        }

        return $rules;
    }

    public function updatedChequeImage()
    {
        if ($this->cheque_image) {
            Log::info('Cheque image uploaded', [
                'name' => $this->cheque_image->getClientOriginalName(),
                'size' => $this->cheque_image->getSize(),
                'mime' => $this->cheque_image->getMimeType()
            ]);
        }
    }

    public function updatedTransferReceiptImage()
    {
        if ($this->transfer_receipt_image) {
            Log::info('Transfer receipt image uploaded', [
                'name' => $this->transfer_receipt_image->getClientOriginalName(),
                'size' => $this->transfer_receipt_image->getSize(),
                'mime' => $this->transfer_receipt_image->getMimeType()
            ]);
        }
    }

    public function save()
    {
        Log::info('Attempting to save receipt', ['receipt_id' => $this->receipt->id]);

        // Prepare data for update
        $data = [
            'receipt_category' => $this->receipt_category,
            'payment_type' => $this->payment_type,
            'amount' => $this->amount,
            'receipt_date' => $this->receipt_date,
            'narration' => $this->narration,
            'status' => $this->status,
            'vat_rate' => $this->vat_rate,
            'vat_amount' => $this->vat_amount,
            'vat_inclusive' => $this->vat_inclusive,
            'cheque_no' => $this->payment_type === 'CHEQUE' ? $this->cheque_no : null,
            'cheque_bank' => $this->payment_type === 'CHEQUE' ? $this->cheque_bank : null,
            'cheque_date' => $this->payment_type === 'CHEQUE' ? $this->cheque_date : null,
            'transaction_reference' => $this->payment_type === 'ONLINE_TRANSFER' ? $this->transaction_reference : null,
            'deposit_date' => $this->receipt->deposit_date, // Retain original unless overridden
            'remarks' => $this->receipt->remarks, // Retain original unless overridden
        ];

        // Apply specific logic for CASH and ONLINE_TRANSFER
        if (in_array($this->payment_type, ['CASH', 'ONLINE_TRANSFER'])) {
            $data['status'] = 'CLEARED';
            if (!empty($this->receipt_date)) {
                $data['deposit_date'] = $this->receipt_date;
            }
            $data['remarks'] = $this->narration; // Set remarks from narration
        } else if ($this->payment_type === 'CHEQUE') {
            // If changed back to CHEQUE, ensure status is appropriate (e.g., PENDING if not already cleared/bounced)
            if ($this->originalPaymentType !== 'CHEQUE' && !in_array($this->status, ['CLEARED', 'BOUNCED'])) {
                $data['status'] = 'PENDING';
            }
            // Clear deposit date and remarks if status is PENDING for a cheque
            if ($data['status'] === 'PENDING') {
                $data['deposit_date'] = null;
                $data['remarks'] = null;
            }
        } else {
            // If it's not CASH, ONLINE_TRANSFER, or CHEQUE (shouldn't happen with validation)
            // Default to PENDING, clear deposit date and remarks
            $data['status'] = 'PENDING';
            $data['deposit_date'] = null;
            $data['remarks'] = null;
        }

        // Validate based on potentially modified data
        $this->validate($this->rules());

        try {
            DB::beginTransaction();
            Log::info('Updating receipt with data:', $data);

            // Update the receipt
            $this->receipt->update($data);

            // Handle Cheque Image Upload/Removal
            if ($this->payment_type === 'CHEQUE') {
                if ($this->remove_cheque_image) {
                    Log::info('Removing cheque image');
                    $this->receipt->clearMediaCollection('cheque_images');
                }
                if ($this->cheque_image) {
                    Log::info('Adding new cheque image');
                    $this->receipt->clearMediaCollection('cheque_images'); // Remove old before adding new
                    $this->receipt->addMedia($this->cheque_image->getRealPath())
                        ->usingName('Cheque_' . $this->cheque_no)
                        ->usingFileName('cheque_' . $this->receipt->id . '_' . time() . '.' . $this->cheque_image->getClientOriginalExtension())
                        ->toMediaCollection('cheque_images');
                    $this->reset('cheque_image');
                }
            }

            // Handle Transfer Receipt Image Upload/Removal
            if ($this->payment_type === 'ONLINE_TRANSFER') {
                if ($this->remove_transfer_image) {
                    Log::info('Removing transfer receipt image');
                    $this->receipt->clearMediaCollection('transfer_receipts');
                }
                if ($this->transfer_receipt_image) {
                    Log::info('Adding new transfer receipt image');
                    $this->receipt->clearMediaCollection('transfer_receipts'); // Remove old before adding new
                    $this->receipt->addMedia($this->transfer_receipt_image->getRealPath())
                        ->usingName('TransferReceipt_' . $this->transaction_reference)
                        ->usingFileName('transfer_' . $this->receipt->id . '_' . time() . '.' . $this->transfer_receipt_image->getClientOriginalExtension())
                        ->toMediaCollection('transfer_receipts');
                    $this->reset('transfer_receipt_image');
                }
            }

            DB::commit();
            Log::info('Receipt updated successfully');
            session()->flash('success', 'Receipt updated successfully.');
            return redirect()->route('receipts.list-by-contract', $this->contract->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed during receipt update', ['errors' => $e->errors()]);
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating receipt', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Error updating receipt: ' . $e->getMessage());
        }
    }

    // Method to fix a single cash receipt
    public function runCashReceiptFix($receiptId)
    {
        $receipt = Receipt::find($receiptId);
        if ($receipt && $receipt->payment_type === 'CASH') {
            // Ensure all fields are properly cleared
            DB::table('receipts')
                ->where('id', $receipt->id)
                ->update([
                    'status' => 'CLEARED',
                    'cheque_no' => null,
                    'cheque_bank' => null,
                    'cheque_date' => null,
                    'transaction_reference' => null,
                ]);

            // Clear media collections
            $receipt->clearMediaCollection('cheque_images');
            $receipt->clearMediaCollection('transfer_receipts');
        }
    }

    public function delete()
    {
        $contractId = $this->receipt->contract_id;

        // Clear all media associated with the receipt
        $this->receipt->clearMediaCollection('cheque_images');
        $this->receipt->clearMediaCollection('transfer_receipts');

        // Delete the receipt
        $this->receipt->delete();

        session()->flash('success', 'Receipt deleted successfully.');

        return redirect()->route('receipts.list-by-contract', $contractId);
    }

    public function render()
    {
        return view('livewire.receipts.edit');
    }
}
