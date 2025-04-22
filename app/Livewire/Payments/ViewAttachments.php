<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

class ViewAttachments extends Component
{
    public bool $showModal = false;
    public ?Payment $payment = null;
    public Collection $attachments;

    #[On('showPaymentAttachments')]
    public function loadAttachments(int $paymentId): void
    {
        $this->payment = Payment::with('media')->find($paymentId);

        if ($this->payment) {
            $this->attachments = $this->payment->getMedia('receipts');
            $this->showModal = true;
        } else {
            // Handle case where payment is not found, maybe dispatch an error notification
            $this->dispatch('notify', title: 'Error', message: 'Could not find payment attachments.', type: 'error');
            $this->closeModal();
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->payment = null;
        $this->attachments = collect(); // Reset attachments
    }

    // Note: Actual file viewing/download might need dedicated routes depending on complexity/security needs
    // For simplicity here, we just provide the URL.

    public function render()
    {
        return view('livewire.payments.view-attachments');
    }
}
