<?php

namespace App\Livewire\Payments;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Carbon\Carbon;

class PaymentForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public ?Payment $payment = null;
    public bool $editing = false;

    #[Rule('required|integer|exists:properties,id')]
    public ?int $property_id = null;

    #[Rule('nullable|integer|exists:contracts,id')]
    public ?int $contract_id = null;

    #[Rule('required|integer|exists:payment_types,id')]
    public ?int $payment_type_id = null;

    #[Rule('required|numeric|min:0')]
    public ?string $amount = null;

    #[Rule('required|date')]
    public ?string $paid_at = null;

    #[Rule('required|string|in:cash,cheque,bank_transfer,credit_card')]
    public ?string $payment_method = 'cash';

    #[Rule('nullable|string|max:100')]
    public ?string $reference_number = null;

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('nullable|array')]
    #[Rule('nullable|max:5')]
    public $attachments = [];

    #[Rule('nullable|array')]
    public $existingAttachments = [];

    public $attachmentsToRemove = [];

    public function mount(?Payment $payment = null): void
    {
        if ($payment) {
            $this->payment = $payment;
            $this->editing = true;
            Gate::authorize('edit payments');
            $this->property_id = $this->payment->property_id;
            $this->contract_id = $this->payment->contract_id;
            $this->payment_type_id = $this->payment->payment_type_id;
            $this->amount = (string) $this->payment->amount;
            $this->paid_at = $this->payment->paid_at?->format('Y-m-d');
            $this->payment_method = $this->payment->payment_method;
            $this->reference_number = $this->payment->reference_number;
            $this->description = $this->payment->description;
            $this->existingAttachments = $this->payment->getMedia('receipts')->toArray();
        } else {
            Gate::authorize('create payments');
            $this->paid_at = Carbon::today()->format('Y-m-d');
        }
    }

    #[Computed]
    public function properties(): Collection
    {
        return Property::orderBy('name')->get(['id', 'name', 'property_no'])->mapWithKeys(function ($property) {
            return [$property->id => $property->name . ' (' . $property->property_no . ')'];
        });
    }

    #[Computed]
    public function paymentTypes(): Collection
    {
        return PaymentType::orderBy('name')->pluck('name', 'id');
    }

    #[Computed]
    public function contracts(): Collection
    {
        if (!$this->property_id) {
            return collect();
        }
        return Contract::where('property_id', $this->property_id)
            ->orderBy('cstart', 'desc')
            ->get(['id', 'name', 'cstart', 'cend'])
            ->mapWithKeys(function ($contract) {
                $dateRange = Carbon::parse($contract->cstart)->format('d/m/Y') . ' - ' .
                    Carbon::parse($contract->cend)->format('d/m/Y');
                return [$contract->id => $contract->name . ' (' . $dateRange . ')'];
            });
    }

    // Automatically clear contract_id if property changes
    public function updatedPropertyId($value): void
    {
        $this->contract_id = null;
        $this->resetValidation('contract_id');
    }

    // Called when `wire:model` updates `$attachments`
    public function updatedAttachments(): void
    {
        $this->validateOnly('attachments.*', [
            'attachments.*' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'] // Max 10MB, specific types
        ]);
    }

    public function removeUpload(string $name, string $key): void
    {
        foreach ($this->attachments as $i => $file) {
            if ($file->getFilename() === $name) {
                unset($this->attachments[$i]);
                break;
            }
        }
        $this->attachments = array_values($this->attachments);
    }

    public function markAttachmentForRemoval(int $mediaId): void
    {
        if (!in_array($mediaId, $this->attachmentsToRemove)) {
            $this->attachmentsToRemove[] = $mediaId;
        }
    }

    public function unmarkAttachmentForRemoval(int $mediaId): void
    {
        $this->attachmentsToRemove = array_diff($this->attachmentsToRemove, [$mediaId]);
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->editing) {
            Gate::authorize('edit payments');
            $this->payment->update($validated);
            $payment = $this->payment->fresh();
            $message = 'Payment updated successfully.';
        } else {
            Gate::authorize('create payments');
            $validated['user_id'] = Auth::id();
            $payment = Payment::create($validated);
            $message = 'Payment created successfully.';
        }

        // Handle existing attachments removal
        if (!empty($this->attachmentsToRemove)) {
            Media::whereIn('id', $this->attachmentsToRemove)->delete();
        }

        // Handle new file uploads
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $payment->addMedia($attachment->getRealPath())
                    ->usingName($attachment->getClientOriginalName())
                    ->toMediaCollection('receipts');
            }
        }

        $this->dispatch('notify', title: 'Success', message: $message, type: 'success');

        return redirect()->route('payments.index');
    }

    public function render()
    {
        return view('livewire.payments.payment-form');
    }
}
