<?php

namespace App\Livewire\Payments;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Layout('components.layouts.app')]
class Edit extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public Payment $payment;

    #[Rule('required|exists:properties,id')]
    public $property_id = '';

    #[Rule('nullable|exists:contracts,id')]
    public $contract_id = '';

    #[Rule('required|exists:payment_types,id')]
    public $payment_type_id = '';

    #[Rule('required|numeric|min:0.01')]
    public $amount = '';

    #[Rule('required|date')]
    public $paid_at;

    #[Rule('nullable|string|max:1000')]
    public $description = '';

    #[Rule([
        'new_attachments' => 'nullable|array',
        'new_attachments.*' => 'file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx'
    ])]
    public $new_attachments = [];

    public $existing_attachments;

    public $properties = [];
    public $contracts = [];
    public $paymentTypes = [];

    public function mount(Payment $payment)
    {
        $this->authorize('update', $payment);
        $this->payment = $payment;

        // Load related data for dropdowns
        // Format properties for standard select (already ordered by name)
        $this->properties = Property::orderBy('name')->pluck('name', 'id');

        $this->paymentTypes = PaymentType::orderBy('name')->pluck('name', 'id');

        // Populate fields from the model
        $this->property_id = $payment->property_id;
        $this->contract_id = $payment->contract_id;
        $this->payment_type_id = $payment->payment_type_id;
        $this->amount = number_format($payment->amount, 2, '.', ''); // Format for input
        $this->paid_at = $payment->paid_at->format('Y-m-d');
        $this->description = $payment->description;

        // Load ALL existing attachments
        $this->existing_attachments = $payment->getMedia('attachments');

        // Load contracts for the current property
        $this->loadContractsForProperty($this->property_id);
    }

    // When property_id changes, update the available contracts
    public function updatedPropertyId($value)
    {
        $this->loadContractsForProperty($value);
        $this->contract_id = ''; // Reset contract selection
    }

    protected function loadContractsForProperty($propertyId)
    {
        if (!empty($propertyId)) {
            // Fetch ALL contracts (both active and inactive)
            $this->contracts = Contract::where('property_id', $propertyId)
                ->select('id', 'name', 'validity') // Select necessary fields
                ->get()
                ->sortByDesc(function ($contract) {
                    // Sort active contracts first
                    return $contract->validity === 'YES';
                })
                ->mapWithKeys(function ($contract) {
                    // Add '(Active)' marker only to active contracts
                    $isActive = $contract->validity === 'YES';
                    return [$contract->id => $contract->name . ($isActive ? ' (Active)' : '')];
                });
        } else {
            $this->contracts = collect();
        }
    }

    public function update()
    {
        $this->authorize('update', $this->payment);
        $validatedData = $this->validate();

        // Remove attachment props from validated data before update
        unset($validatedData['new_attachments']);

        // Convert empty contract_id to null
        if (empty($validatedData['contract_id'])) {
            $validatedData['contract_id'] = null;
        }

        $this->payment->update($validatedData);

        // Handle multiple new attachments
        if (!empty($this->new_attachments)) {
            foreach ($this->new_attachments as $attachment) {
                $this->payment->addMedia($attachment->getRealPath())
                    ->usingName($attachment->getClientOriginalName())
                    ->toMediaCollection('attachments');
            }
            // Reset after upload
            $this->new_attachments = [];
            // Refresh existing attachments list
            $this->existing_attachments = $this->payment->getMedia('attachments');
        }

        session()->flash('message', 'Payment successfully updated.');
        // $this->dispatch('notify', title: 'Success', message: 'Payment updated successfully', type: 'success');

        return $this->redirectRoute('payments.index'); // Assuming route name
    }

    public function removeAttachment($mediaId)
    {
        $this->authorize('update', $this->payment);
        $mediaItem = Media::find($mediaId);

        // Ensure the media item belongs to this payment before deleting
        if ($mediaItem && $mediaItem->model_id === $this->payment->id && $mediaItem->model_type === get_class($this->payment)) {
            $mediaItem->delete();
            $this->existing_attachments = $this->payment->getMedia('attachments'); // Refresh the list
            // Optionally add notification
            session()->flash('message', 'Attachment removed.'); // Example notification
        } else {
            session()->flash('error', 'Could not remove attachment.');
        }
    }

    public function render()
    {
        return view('livewire.payments.edit');
    }
}
