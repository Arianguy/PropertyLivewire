<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use App\Models\Property;
use App\Models\PaymentType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    // Removed HasPagination, WithSorting, Modifiable traits

    public string $search = '';
    public ?int $propertyId = null;
    public ?int $paymentTypeId = null;

    // Sorting properties (default values)
    public string $sortBy = 'paid_at';
    public string $sortDirection = 'desc';

    // Pagination property (default value)
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'propertyId' => ['except' => null],
        'paymentTypeId' => ['except' => null],
        'sortBy' => ['except' => 'paid_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount(): void
    {
        Gate::authorize('view payments');
        // Default sort already set in properties
    }

    // Method to toggle sort direction when column header is clicked
    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPropertyId(): void
    {
        $this->resetPage();
    }

    public function updatingPaymentTypeId(): void
    {
        $this->resetPage();
    }

    public function getPropertiesProperty()
    {
        // Fetch properties and format as id => name array for the select dropdown
        return Property::query()
            ->orderBy('name')
            ->pluck('name', 'id'); // Use pluck to get id => name
    }

    public function getPaymentTypesProperty()
    {
        // Fetch payment types and format as id => name array for the select dropdown
        return PaymentType::query()
            ->orderBy('name')
            ->pluck('name', 'id'); // Use pluck to get id => name
    }

    public function delete(int $paymentId): void // Accept ID instead of model for simpler deletion
    {
        Gate::authorize('delete payments');

        $payment = Payment::findOrFail($paymentId);

        // Consider wrapping in a transaction if media deletion failure should prevent payment deletion
        $payment->clearMediaCollection('receipts');
        $payment->delete();

        $this->dispatch('notify', title: 'Success', message: 'Payment deleted successfully.', type: 'success');
    }

    // Dispatch event to open attachments modal
    public function showAttachments(int $paymentId): void
    {
        $this->dispatch('showPaymentAttachments', paymentId: $paymentId);
    }

    public function render()
    {
        $query = Payment::query()
            ->with(['property', 'paymentType', 'contract', 'user'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('property', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('paymentType', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('contract', fn($q) => $q->where('contract_number', 'like', '%' . $search . '%')); // Assuming contract_number field
                });
            })
            ->when($this->propertyId, fn($q, $id) => $q->where('property_id', $id))
            ->when($this->paymentTypeId, fn($q, $id) => $q->where('payment_type_id', $id))
            ->orderBy($this->sortBy, $this->sortDirection);

        $payments = $query->paginate($this->perPage);

        return view('livewire.payments.index', [
            'payments' => $payments,
        ]);
        // No explicit layout specified here, will use default
    }
}
