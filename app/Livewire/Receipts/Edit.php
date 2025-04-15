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
            'receipt_category' => 'required|in:SECURITY_DEPOSIT,RENT',
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
        try {
            Log::info('Starting receipt update process');

            // Validate the form fields
            $this->validate([
                'receipt_category' => 'required|string',
                'payment_type' => 'required|in:CASH,CHEQUE,ONLINE_TRANSFER',
                'amount' => 'required|numeric|min:0.01',
                'receipt_date' => 'required|date',
                'narration' => 'required|string|max:255',
                'cheque_no' => 'required_if:payment_type,CHEQUE|nullable|string',
                'cheque_date' => 'required_if:payment_type,CHEQUE|nullable|date',
                'cheque_bank' => 'required_if:payment_type,CHEQUE|nullable|string',
                'transaction_reference' => 'required_if:payment_type,ONLINE_TRANSFER|nullable|string',
            ]);

            Log::info('Receipt validation passed', [
                'payment_type' => $this->payment_type,
                'has_cheque_image' => isset($this->cheque_image),
                'has_transfer_image' => isset($this->transfer_receipt_image),
                'remove_cheque_image' => $this->remove_cheque_image,
                'remove_transfer_image' => $this->remove_transfer_image
            ]);

            // Begin transaction
            DB::beginTransaction();

            try {
                // Prepare the update data
                $updateData = [
                    'receipt_category' => $this->receipt_category,
                    'payment_type' => $this->payment_type,
                    'amount' => $this->amount,
                    'receipt_date' => $this->receipt_date,
                    'narration' => $this->narration,
                ];

                // Handle payment-specific fields
                if ($this->payment_type === 'CASH') {
                    $updateData['status'] = 'CLEARED';
                    $updateData['cheque_no'] = null;
                    $updateData['cheque_bank'] = null;
                    $updateData['cheque_date'] = null;
                    $updateData['transaction_reference'] = null;

                    // Clear media collections for cash payments
                    $this->receipt->clearMediaCollection('cheque_images');
                    $this->receipt->clearMediaCollection('transfer_receipts');

                    Log::info('Processing CASH payment type - cleared media collections');
                } else if ($this->payment_type === 'CHEQUE') {
                    $updateData['status'] = $this->status;
                    $updateData['cheque_no'] = $this->cheque_no;
                    $updateData['cheque_bank'] = $this->cheque_bank;
                    $updateData['cheque_date'] = $this->cheque_date;
                    $updateData['transaction_reference'] = null;

                    // Handle cheque image
                    if ($this->remove_cheque_image) {
                        Log::info('Removing existing cheque image');
                        $this->receipt->clearMediaCollection('cheque_images');
                    }

                    if ($this->cheque_image) {
                        Log::info('Adding new cheque image', [
                            'filename' => $this->cheque_image->getClientOriginalName()
                        ]);
                        $this->receipt->clearMediaCollection('cheque_images');
                        $this->receipt->addMedia($this->cheque_image->getRealPath())
                            ->usingName($this->receipt->contract->name . '_cheque')
                            ->toMediaCollection('cheque_images', 'public');
                    }

                    // Clear transfer media for cheque payments
                    $this->receipt->clearMediaCollection('transfer_receipts');

                    Log::info('Processed CHEQUE payment type');
                } else if ($this->payment_type === 'ONLINE_TRANSFER') {
                    $updateData['status'] = $this->status;
                    $updateData['transaction_reference'] = $this->transaction_reference;
                    $updateData['cheque_no'] = null;
                    $updateData['cheque_bank'] = null;
                    $updateData['cheque_date'] = null;

                    // Handle transfer image
                    if ($this->remove_transfer_image) {
                        Log::info('Removing existing transfer receipt image');
                        $this->receipt->clearMediaCollection('transfer_receipts');
                    }

                    if ($this->transfer_receipt_image) {
                        Log::info('Adding new transfer receipt image', [
                            'filename' => $this->transfer_receipt_image->getClientOriginalName()
                        ]);
                        $this->receipt->clearMediaCollection('transfer_receipts');
                        $this->receipt->addMedia($this->transfer_receipt_image->getRealPath())
                            ->usingName($this->receipt->contract->name . '_transfer')
                            ->toMediaCollection('transfer_receipts', 'public');
                    }

                    // Clear cheque media for transfer payments
                    $this->receipt->clearMediaCollection('cheque_images');

                    Log::info('Processed ONLINE_TRANSFER payment type');
                }

                // Update the receipt
                $this->receipt->update($updateData);
                Log::info('Receipt data updated in database');

                // Commit the transaction
                DB::commit();
                Log::info('Database transaction committed');

                // Refresh the receipt
                $this->receipt = $this->receipt->fresh();

                // Success message
                session()->flash('success', 'Receipt updated successfully.');

                // Redirect to the receipts list
                return redirect()->route('receipts.list-by-contract', $this->receipt->contract_id);
            } catch (\Exception $e) {
                // Rollback in case of error
                DB::rollBack();
                Log::error('Database error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating receipt: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
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
