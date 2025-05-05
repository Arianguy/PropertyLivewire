<?php

namespace App\Livewire\Reports;

// Copied and adapted from SecurityDepositReport
use App\Models\Contract;
use App\Models\Property;
use App\Models\Receipt; // Use Receipt model
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChequeStatusReportMail; // Use new Mailable
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On; // Add this import

class ChequeStatusReport extends Component
{
    // use WithPagination;

    public $filter = 'upcoming'; // Default to upcoming
    public $search = '';
    public $startDate = null;
    public $endDate = null;

    // Filter type: 'upcoming' or 'cleared'
    public string $filterType = 'upcoming'; // Default to Upcoming Cheques

    public ?string $imageUrlToShow = null; // Add this property

    protected $rules = [
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
    ];

    // Reset pagination hooks (uncomment if using pagination)
    public function updatingSearch()
    { /* $this->resetPage(); */
    }
    public function updatingFilter()
    { /* $this->resetPage(); */
    }
    public function updatingStartDate()
    { /* $this->resetPage(); */
    }
    public function updatingEndDate()
    { /* $this->resetPage(); */
    }

    // Validate that end date is not in the future (only for cleared filter)
    public function updatedEndDate($value)
    {
        if ($this->filter === 'cleared' && $value && \Carbon\Carbon::parse($value)->isFuture()) {
            $this->endDate = now()->format('Y-m-d');
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'End date cannot be in the future. Resetting to today.']);
        }
    }

    public function setFilter($status)
    {
        if (in_array($status, ['cleared', 'upcoming'])) {
            $this->filter = $status;
        }
    }

    public function clearDates()
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    private function getReportData()
    {
        $searchTerm = '%' . $this->search . '%';
        $now = now();
        $userName = Auth::user() ? Auth::user()->name : 'System';

        $data = [
            'reportData' => collect(),
            'grandTotals' => [],
            'columns' => [],
            'currentFilter' => $this->filter,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'generatedAt' => $now,
            'generatedBy' => $userName,
        ];

        // Base query for receipts of type CHEQUE
        $baseQuery = Receipt::with(['contract.property', 'contract.tenant'])
            ->where('payment_type', 'CHEQUE')
            ->join('contracts', 'receipts.contract_id', '=', 'contracts.id')
            ->join('properties', 'contracts.property_id', '=', 'properties.id')
            ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
            ->select(
                'receipts.*' // Select all receipt fields
                // Explicitly select related fields needed to avoid ambiguity
                ,
                'properties.name as property_name',
                'tenants.name as tenant_name',
                'contracts.name as contract_name'
            )
            ->where(function ($q) use ($searchTerm) {
                $q->where('properties.name', 'like', $searchTerm)
                    ->orWhere('tenants.name', 'like', $searchTerm)
                    ->orWhere('contracts.name', 'like', $searchTerm)
                    ->orWhere('receipts.cheque_no', 'like', $searchTerm); // Corrected column name
            });

        if ($this->filter === 'cleared') {
            $data['columns'] = ['#', 'Property', 'Tenant', 'Contract #', 'Cheque #', 'Bank Name', 'Cleared On', 'Amount', 'Status'];

            $query = $baseQuery->where('receipts.status', 'CLEARED');

            // Apply date filters (now on deposit_date for cleared cheques)
            if ($this->startDate) {
                $query->whereDate('receipts.deposit_date', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('receipts.deposit_date', '<=', $this->endDate);
            }

            $reportDataCollection = $query->orderBy('receipts.deposit_date', 'desc')->get(); // Order by deposit_date
            $data['reportData'] = $reportDataCollection;
            $data['grandTotals'] = [
                'cheques' => $reportDataCollection->count(),
                'amount' => $reportDataCollection->sum('amount'),
            ];
        } elseif ($this->filter === 'upcoming') {
            $data['columns'] = ['#', 'Property', 'Tenant', 'Contract #', 'Cheque #', 'Bank Name', 'Cheque Date', 'Amount', 'Status'];

            $query = $baseQuery->where('receipts.status', 'PENDING');

            // Apply date filters (on cheque cheque_date) for upcoming
            if ($this->startDate) {
                $query->whereDate('receipts.cheque_date', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('receipts.cheque_date', '<=', $this->endDate);
            }

            $reportDataCollection = $query->orderBy('receipts.cheque_date', 'asc')->get();
            $data['reportData'] = $reportDataCollection;
            $data['grandTotals'] = [
                'cheques' => $reportDataCollection->count(),
                'amount' => $reportDataCollection->sum('amount'),
            ];
        }

        return $data;
    }

    public function render()
    {
        $data = $this->getReportData();
        return view('livewire.reports.cheque-status-report', $data);
    }

    public function exportPdf()
    {
        $data = $this->getReportData();

        if ($data['reportData']->isEmpty()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No data available to export.']);
            return;
        }

        $pdf = Pdf::loadView('pdfs.reports.cheque-status', $data); // Use new PDF view
        $pdf->setPaper('a4', 'landscape');

        $filename = 'cheque-status-report-' . $this->filter . '-' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function emailReport()
    {
        Log::debug('ChequeStatusReport: emailReport method entered.');
        $userId = Auth::id();
        if (!$userId) {
            Log::warning('ChequeStatusReport: Cannot email report, user not authenticated.');
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Authentication required to email report.']);
            return;
        }
        $userEmail = Auth::user()->email;
        if (!$userEmail) {
            Log::warning('ChequeStatusReport: Cannot email report, user has no email address.', ['user_id' => $userId]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Your account does not have an email address configured.']);
            return;
        }

        Log::debug('ChequeStatusReport: Attempting to dispatch email job to: ' . $userEmail, [
            'filter' => $this->filter,
            'search' => $this->search,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'userId' => $userId
        ]);

        try {
            // Use the new Mailable
            Mail::to($userEmail)->send(new ChequeStatusReportMail(
                $this->filter,
                $this->startDate,
                $this->endDate,
                $this->search,
                $userId
            ));
            Log::info('ChequeStatusReport: Email job dispatched successfully for: ' . $userEmail);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Report email queued successfully for ' . $userEmail]);
        } catch (\Exception $e) {
            Log::error("ChequeStatusReport: Email dispatch failed: " . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to queue email. Please check logs.']);
        }

        Log::debug('ChequeStatusReport: emailReport method finished.');
    }

    #[On('openImageModal')] // Listen for the event from the modal if needed for direct communication
    public function handleModalOpen()
    {
        // Optional: Add logic if the parent needs to react to the modal opening
    }

    /**
     * Set the image URL and dispatch event to open the modal.
     */
    public function viewChequeImage(int $receiptId): void
    {
        $receipt = Receipt::find($receiptId);
        if ($receipt && $receipt->hasMedia('cheque_images')) {
            $this->imageUrlToShow = $receipt->getFirstMediaUrl('cheque_images');
            Log::debug('Generated Cheque Image URL: ' . $this->imageUrlToShow);
            $this->dispatch('showImageModal', imageUrl: $this->imageUrlToShow);
        } else {
            $this->imageUrlToShow = null;
            Log::warning('Cheque image not found for receipt ID: ' . $receiptId);
            session()->flash('error', 'Cheque image not found.');
        }
    }

    #[On('imageModalClosed')] // Listen for the event when the modal closes
    public function clearImageUrl(): void
    {
        $this->imageUrlToShow = null;
    }
}
