<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use Livewire\Component;

class ViewAttachment extends Component
{
    public $receipt;
    public $attachmentUrl;
    public $attachmentName;
    public $isOpen = false;

    protected $listeners = ['openAttachment' => 'show'];

    public function show($receiptId)
    {
        $this->receipt = Receipt::findOrFail($receiptId);

        if ($this->receipt->payment_type === 'CHEQUE' && $this->receipt->hasChequeImage()) {
            $media = $this->receipt->getFirstMedia('cheque_images');
            $this->attachmentUrl = route('media.show', ['id' => $media->id]);
            $this->attachmentName = $media->name ?? 'Cheque Image';
        } elseif ($this->receipt->payment_type === 'ONLINE_TRANSFER' && $this->receipt->hasTransferReceiptImage()) {
            $media = $this->receipt->getFirstMedia('transfer_receipts');
            $this->attachmentUrl = route('media.show', ['id' => $media->id]);
            $this->attachmentName = $media->name ?? 'Transfer Receipt';
        } else {
            $this->attachmentUrl = null;
            $this->attachmentName = 'No Attachment Available';
        }

        $this->dispatch('open-attachment');
    }

    public function downloadAttachment()
    {
        if ($this->receipt->payment_type === 'CHEQUE' && $this->receipt->hasChequeImage()) {
            return response()->download($this->receipt->getChequeImagePath(), 'cheque_image.jpg');
        } elseif ($this->receipt->payment_type === 'ONLINE_TRANSFER' && $this->receipt->hasTransferReceiptImage()) {
            return response()->download($this->receipt->getTransferReceiptImagePath(), 'transfer_receipt.jpg');
        }
    }

    public function render()
    {
        return view('livewire.receipts.view-attachment');
    }
}
