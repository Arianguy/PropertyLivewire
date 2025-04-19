<?php

namespace App\Livewire;

use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChequeManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $selectedCheque = null;
    public $showClearModal = false;
    // Properties for the Clear Cheque Modal
    public $clear_depositDate;
    public $clear_status = '';
    public $clear_remarks = '';

    public $showImageModal = false;
    public $attachmentUrl = null;
    public $attachmentName = null;
    public $chequeImage = null;

    // Listen for the event emitted by ResolveBouncedReceipt
    protected $listeners = ['receiptsUpdated' => '$refresh'];

    // Note: Validation rules key names must match the public properties
    protected function rules()
    {
        return [
            'clear_depositDate' => 'required|date',
            'clear_status' => 'required|in:CLEARED,BOUNCED',
            'clear_remarks' => 'required_if:clear_status,BOUNCED|nullable|string|max:1000',
            'chequeImage' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf', // For image upload modal if used
        ];
    }

    public function mount()
    {
        $this->clear_depositDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $cheques = Receipt::where('payment_type', 'CHEQUE')
            ->where(function ($query) {
                $query->where('status', 'PENDING')
                    ->orWhere(function ($q) {
                        $q->where('status', 'BOUNCED')
                            ->whereRaw('receipts.amount > (SELECT COALESCE(SUM(amount), 0) FROM receipts as res WHERE res.resolves_receipt_id = receipts.id)');
                    });
            })
            ->with(['contract.tenant', 'contract.property', 'resolutionReceipts'])
            ->withSum('resolutionReceipts', 'amount')
            ->orderBy('cheque_date', 'asc')
            ->paginate(10);

        return view('livewire.cheque-management', [
            'cheques' => $cheques
        ]);
    }

    public function showClearChequeModal($chequeId)
    {
        Log::info("Attempting to open clear modal for Cheque ID: {$chequeId}");
        try {
            $this->selectedCheque = Receipt::findOrFail($chequeId);
            // Reset the correct properties for the clear modal
            $this->reset(['clear_status', 'clear_remarks']);
            $this->clear_depositDate = now()->format('Y-m-d'); // Ensure date is reset
            $this->showClearModal = true;
            Log::info("showClearModal property set to true for Cheque ID: {$chequeId}");
        } catch (\Exception $e) {
            Log::error('Error opening clear cheque modal', ['error' => $e->getMessage()]);
            $this->dispatch('notify', [
                'message' => 'Error preparing cheque clearing. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function clearCheque()
    {
        // Validate using the properties bound to the modal
        $validatedData = $this->validate();
        Log::info('Clear Cheque Validation Passed', $validatedData);

        try {
            if (!$this->selectedCheque) {
                throw new \Exception('No cheque selected to clear.');
            }

            Log::info('Updating cheque status', [
                'cheque_id' => $this->selectedCheque->id,
                'data' => $validatedData
            ]);

            $this->selectedCheque->update([
                'deposit_date' => $validatedData['clear_depositDate'],
                'deposit_account' => '019100503669', // Keep the fixed account
                'status' => $validatedData['clear_status'],
                'remarks' => $validatedData['clear_remarks'],
            ]);

            Log::info('Cheque update successful', ['cheque_id' => $this->selectedCheque->id]);

            // Reset the correct properties and close modal
            $this->reset(['selectedCheque', 'clear_status', 'clear_remarks', 'showClearModal']);
            $this->clear_depositDate = now()->format('Y-m-d');

            $this->dispatch('notify', [
                'message' => 'Cheque status updated successfully!',
                'type' => 'success'
            ]);
            $this->dispatch('receiptsUpdated'); // Refresh tables if needed

        } catch (\Exception $e) {
            Log::error('Error clearing cheque', ['error' => $e->getMessage()]);
            $this->dispatch('notify', [
                'message' => $e->getMessage() ?: 'Error updating cheque status. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function viewChequeImage($chequeId)
    {
        try {
            $this->selectedCheque = Receipt::findOrFail($chequeId);
            $this->attachmentUrl = null;
            $this->attachmentName = null;

            if ($this->selectedCheque->hasChequeImage()) {
                $media = $this->selectedCheque->getFirstMedia('cheque_images');
                $this->attachmentUrl = $media->getUrl();
                $this->attachmentName = $media->name ?? 'Cheque Image';
            }

            $this->showImageModal = true;
        } catch (\Exception $e) {
            Log::error('Error viewing cheque image', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify', [
                'message' => 'Error viewing cheque image: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function updatedChequeImage()
    {
        $this->validate([
            'chequeImage' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        try {
            if (!$this->selectedCheque) {
                throw new \Exception('No cheque selected for image upload');
            }

            $media = $this->selectedCheque
                ->addMedia($this->chequeImage->getRealPath())
                ->usingName('Cheque_' . $this->selectedCheque->cheque_no)
                ->usingFileName('cheque_' . $this->selectedCheque->id . '_' . time() . '.' . $this->chequeImage->getClientOriginalExtension())
                ->toMediaCollection('cheque_images');

            $this->attachmentUrl = $media->getUrl();
            $this->attachmentName = $media->name;

            $this->reset('chequeImage');

            $this->dispatch('notify', [
                'message' => 'Cheque image uploaded successfully!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading cheque image', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify', [
                'message' => 'Error uploading cheque image: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function downloadAttachment()
    {
        if (!$this->selectedCheque || !$this->selectedCheque->hasChequeImage()) {
            return;
        }

        $media = $this->selectedCheque->getFirstMedia('cheque_images');
        return response()->download($media->getPath(), $media->name);
    }
}
