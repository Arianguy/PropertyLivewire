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
    public $depositDate;
    public $showClearModal = false;
    public $status = '';
    public $remarks = '';

    public $showImageModal = false;
    public $attachmentUrl = null;
    public $attachmentName = null;
    public $chequeImage = null;

    protected function rules()
    {
        return [
            'depositDate' => 'required|date',
            'status' => 'required|in:CLEARED,BOUNCED',
            'remarks' => 'required_if:status,BOUNCED|nullable|string|max:1000',
            'chequeImage' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ];
    }

    public function mount()
    {
        $this->depositDate = now()->format('Y-m-d');
    }

    public function render()
    {
        $cheques = Receipt::where('payment_type', 'CHEQUE')
            ->where('status', 'PENDING')
            ->with(['contract.tenant', 'contract.property'])
            ->orderBy('cheque_date', 'asc')
            ->paginate(10);

        return view('livewire.cheque-management', [
            'cheques' => $cheques
        ]);
    }

    public function showClearChequeModal($chequeId)
    {
        try {
            $this->selectedCheque = Receipt::findOrFail($chequeId);
            $this->reset(['status', 'remarks']);
            $this->depositDate = now()->format('Y-m-d');
            $this->showClearModal = true;
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
        $this->validate();

        try {
            if (!$this->selectedCheque) {
                throw new \Exception('No cheque selected to clear.');
            }

            $this->selectedCheque->update([
                'deposit_date' => $this->depositDate,
                'deposit_account' => '019100503669',
                'status' => $this->status,
                'remarks' => $this->remarks,
            ]);

            $this->reset(['selectedCheque', 'status', 'remarks', 'showClearModal']);
            $this->depositDate = now()->format('Y-m-d');

            $this->dispatch('notify', [
                'message' => 'Cheque status updated successfully!',
                'type' => 'success'
            ]);
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
