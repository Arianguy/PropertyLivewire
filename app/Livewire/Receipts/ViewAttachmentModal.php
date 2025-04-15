<?php

namespace App\Livewire\Receipts;

use App\Models\Receipt;
use Livewire\Component;

class ViewAttachmentModal extends Component
{
    public $showModal = false;
    public $receipt;
    public $attachmentType;
    public $attachmentUrl;
    public $attachmentName;

    protected $listeners = ['showAttachment'];

    public function showAttachment(Receipt $receipt, $type)
    {
        $this->receipt = $receipt;
        $this->attachmentType = $type;

        if ($type === 'cheque') {
            $media = $receipt->getFirstMedia('cheque_images');
        } else {
            $media = $receipt->getFirstMedia('transfer_receipts');
        }

        if ($media) {
            $this->attachmentUrl = $media->getUrl();
            $this->attachmentName = $media->name;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['receipt', 'attachmentType', 'attachmentUrl', 'attachmentName']);
    }

    public function downloadAttachment()
    {
        if ($this->attachmentType === 'cheque') {
            $media = $this->receipt->getFirstMedia('cheque_images');
        } else {
            $media = $this->receipt->getFirstMedia('transfer_receipts');
        }

        return response()->download($media->getPath(), $media->name);
    }

    public function render()
    {
        return view('livewire.receipts.view-attachment-modal');
    }
}
