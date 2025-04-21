<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Property;
use App\Models\PaymentType;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $sortField = 'paid_at';
    public $sortDirection = 'desc';
    public $propertyIdFilter = '';
    public $paymentTypeIdFilter = '';
    public $perPage = 10;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPropertyIdFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentTypeIdFilter()
    {
        $this->resetPage();
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        $this->authorize('delete', $payment);
        $payment->delete();
        session()->flash('message', 'Payment successfully deleted.');
    }

    public function render()
    {
        $this->authorize('viewAny', Payment::class);

        $query = Payment::with(['property', 'paymentType', 'contract', 'user'])
            ->search($this->search)
            ->when($this->propertyIdFilter, function ($q) {
                $q->where('property_id', $this->propertyIdFilter);
            })
            ->when($this->paymentTypeIdFilter, function ($q) {
                $q->where('payment_type_id', $this->paymentTypeIdFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $payments = $query->paginate($this->perPage);

        $properties = Property::orderBy('name')->pluck('name', 'id');
        $paymentTypes = PaymentType::orderBy('name')->pluck('name', 'id');

        return view('livewire.payments.index', [
            'payments' => $payments,
            'properties' => $properties,
            'paymentTypes' => $paymentTypes,
        ]);
    }
}
