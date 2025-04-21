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

#[Layout('components.layouts.app')]
class Create extends Component
{
    use WithFileUploads, AuthorizesRequests;

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

    #[Rule('nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx')] // 10MB Max
    public $attachment;

    public $properties = [];
    public $contracts = [];
    public $paymentTypes = [];

    public function mount()
    {
        $this->authorize('create', Payment::class);
        $this->properties = Property::orderBy('name')->pluck('name', 'id');
        // Initially load contracts for the first property if available, or leave empty
        $this->contracts = collect(); // Initialize as empty collection
        $this->paymentTypes = PaymentType::orderBy('name')->pluck('name', 'id');
        $this->paid_at = now()->format('Y-m-d'); // Default to today
    }

    // When property_id changes, update the available contracts
    public function updatedPropertyId($value)
    {
        if (!empty($value)) {
            // Fetch ALL contracts (both active and inactive)
            $this->contracts = Contract::where('property_id', $value)
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
        $this->contract_id = ''; // Reset contract selection
    }

    public function save()
    {
        $this->authorize('create', Payment::class);
        $validatedData = $this->validate();
        $validatedData['user_id'] = auth()->id();

        // Convert empty contract_id to null
        if (empty($validatedData['contract_id'])) {
            $validatedData['contract_id'] = null;
        }

        $payment = Payment::create($validatedData);

        if ($this->attachment) {
            $payment->addMedia($this->attachment->getRealPath())
                ->usingName($this->attachment->getClientOriginalName())
                ->toMediaCollection('attachments');
        }

        session()->flash('message', 'Payment successfully created.');
        // $this->dispatch('notify', title: 'Success', message: 'Payment created successfully', type: 'success');

        return $this->redirectRoute('payments.index'); // Assuming route name
    }

    public function render()
    {
        return view('livewire.payments.create');
    }
}
