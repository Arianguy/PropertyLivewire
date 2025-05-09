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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PaymentReportMail; // Uncommented and assuming it's in App\Mail

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    // Removed HasPagination, WithSorting, Modifiable traits

    public string $search = '';
    public ?int $propertyId = null;
    public ?int $paymentTypeId = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    // Sorting properties (default values)
    public string $sortBy = 'paid_at';
    public string $sortDirection = 'desc';

    // Pagination property (default value)
    public int $perPage = 15;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'propertyId' => ['except' => null],
        'paymentTypeId' => ['except' => null],
        'startDate' => ['except' => null],
        'endDate' => ['except' => null],
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

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function clearDates(): void
    {
        $this->startDate = null;
        $this->endDate = null;
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

    private function getPaymentsQuery()
    {
        return Payment::query()
            ->with(['property', 'paymentType', 'contract', 'user'])
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('property', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('paymentType', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
                        ->orWhereHas('contract', fn($q) => $q->where('contract_number', 'like', '%' . $search . '%'));
                });
            })
            ->when($this->propertyId, fn($q, $id) => $q->where('property_id', $id))
            ->when($this->paymentTypeId, fn($q, $id) => $q->where('payment_type_id', $id))
            ->when($this->startDate, fn($q, $date) => $q->whereDate('paid_at', '>=', $date))
            ->when($this->endDate, fn($q, $date) => $q->whereDate('paid_at', '<=', $date))
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    public function render()
    {
        $query = $this->getPaymentsQuery();

        // Clone query for totals before pagination
        $totalQuery = clone $query;
        $grandTotalAmount = $totalQuery->sum('amount');
        $totalPaymentsCount = $totalQuery->count();

        $payments = $query->paginate($this->perPage);

        return view('livewire.payments.index', [
            'payments' => $payments,
            'grandTotalAmount' => $grandTotalAmount,
            'totalPaymentsCount' => $totalPaymentsCount,
        ]);
        // No explicit layout specified here, will use default
    }

    public function exportPdf()
    {
        $payments = $this->getPaymentsQuery()->get();
        $userName = Auth::user() ? Auth::user()->name : 'System';

        if ($payments->isEmpty()) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'No data available to export for the selected criteria.']);
            return;
        }

        $grandTotalAmount = $payments->sum('amount');
        $totalPaymentsCount = $payments->count();

        $data = [
            'payments' => $payments,
            'search' => $this->search,
            'propertyId' => $this->propertyId,
            'paymentTypeId' => $this->paymentTypeId,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'generatedAt' => now(),
            'generatedBy' => $userName,
            'grandTotalAmount' => $grandTotalAmount,
            'totalPaymentsCount' => $totalPaymentsCount,
            // For PDF view, you might want to fetch property and payment type names if IDs are used in filters
            'propertyName' => $this->propertyId ? Property::find($this->propertyId)?->name : null,
            'paymentTypeName' => $this->paymentTypeId ? PaymentType::find($this->paymentTypeId)?->name : null,
        ];

        $pdf = Pdf::loadView('pdfs.reports.payments-list', $data);
        $pdf->setPaper('a4', 'landscape');
        $filename = 'payments-report-' . now()->format('Y-m-d-His') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    protected function sanitizeString(mixed $value): string
    {
        if (!is_string($value)) {
            return (string) $value;
        }
        // Force convert to UTF-8. If it fails, it might return false or an empty string depending on PHP version/settings.
        $value = mb_convert_encoding($value ?? '', 'UTF-8', 'UTF-8');
        // Fallback to strip non-UTF-8 characters if conversion is problematic or incomplete
        // This regex is a common pattern for this, but be cautious if you need to preserve specific multi-byte chars.
        $value = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}\p{S}\s]/u', '', $value);
        return $value ?? ''; // Ensure it's not null
    }

    public function emailReport()
    {
        $user = Auth::user();
        if (!$user || !$user->email) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Your email address is not configured.']);
            return;
        }

        $paymentsQuery = $this->getPaymentsQuery();
        $payments = $paymentsQuery->get();
        $userName = $this->sanitizeString($user->name ?? 'System User');
        $generatedAtFormatted = now()->format('d-M-Y h:i A');

        if ($payments->isEmpty()) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'No data available to email for the selected criteria.']);
            return;
        }

        $sanitizedPayments = $payments->map(function ($payment) {
            $payment->description = $this->sanitizeString($payment->description);
            $payment->reference_number = $this->sanitizeString($payment->reference_number);
            if ($payment->property) {
                $payment->property->name = $this->sanitizeString($payment->property->name);
            }
            if ($payment->contract) {
                $payment->contract->contract_number = $this->sanitizeString($payment->contract->contract_number);
            }
            if ($payment->paymentType) {
                $payment->paymentType->name = $this->sanitizeString($payment->paymentType->name);
            }
            return $payment;
        });

        $grandTotalAmount = $sanitizedPayments->sum('amount');
        $totalPaymentsCount = $sanitizedPayments->count();

        $rawProperty = $this->propertyId ? Property::find($this->propertyId) : null;
        $propertyName = $rawProperty ? $this->sanitizeString($rawProperty->name) : null;

        $rawPaymentType = $this->paymentTypeId ? PaymentType::find($this->paymentTypeId) : null;
        $paymentTypeName = $rawPaymentType ? $this->sanitizeString($rawPaymentType->name) : null;

        $searchSanitized = $this->sanitizeString($this->search);

        try {
            Mail::to($user->email)->send(new PaymentReportMail(
                $sanitizedPayments,
                $searchSanitized,
                $propertyName,
                $paymentTypeName,
                $this->startDate,
                $this->endDate,
                $this->sortBy,
                $this->sortDirection,
                $userName,
                $generatedAtFormatted,
                $grandTotalAmount,
                $totalPaymentsCount
            ));
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Payments report has been queued for email delivery.']);
        } catch (\Exception $e) {
            Log::error('Error queuing payment report email: ' . $e->getMessage(), ['exception' => $e, 'context' => 'PaymentReportMailDispatch']);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not queue the email. Please check logs or contact support.']);
        }
    }
}
