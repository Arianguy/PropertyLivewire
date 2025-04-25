<?php

namespace App\Livewire\Reports;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Receipt;
use App\Models\SecurityDepositSettlement;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination; // Added for potential future pagination
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; // Added for email
use Illuminate\Support\Facades\Mail; // Added for email
use App\Mail\SecurityDepositReportMail; // Added for email
use Illuminate\Support\Facades\Log; // Added for logging

class SecurityDepositReport extends Component
{
    // use WithPagination; // Uncomment if pagination is needed later

    public $filter = 'settled'; // 'settled' or 'unsettled'
    public $search = '';
    public $startDate = null;
    public $endDate = null;

    // Reset pagination when searching or filtering
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

    // Validate that end date is not in the future
    public function updatedEndDate($value)
    {
        if ($value && \Carbon\Carbon::parse($value)->isFuture()) {
            $this->endDate = now()->format('Y-m-d');
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'End date cannot be in the future. Resetting to today.']);
        }
    }

    public function setFilter($status)
    {
        if (in_array($status, ['settled', 'unsettled'])) {
            $this->filter = $status;
            // Reset dates if switching to unsettled where dates don't apply
            // if ($status === 'unsettled') {
            //     $this->clearDates();
            // }
        }
    }

    // Optional: Method to clear date filters
    public function clearDates()
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    // Method to fetch data based on current filters (DRY principle)
    private function getReportData()
    {
        $searchTerm = '%' . $this->search . '%';
        $now = now(); // Get current time
        $userName = Auth::user() ? Auth::user()->name : 'System'; // Get user name

        $data = [
            'reportData' => collect(),
            'grandTotals' => [],
            'columns' => [],
            'currentFilter' => $this->filter,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'generatedAt' => $now,       // Add generation time
            'generatedBy' => $userName, // Add user name
        ];

        if ($this->filter === 'settled') {
            $data['columns'] = [
                '#',
                'Property Name',
                'Tenant Name',
                'Contract #',
                'Settled Contracts',
                'Total Deposit Received',
                'Total Deductions',
                'Total Deposit Refunded',
                'Net Deposit Held'
            ];

            $query = SecurityDepositSettlement::join('contracts', 'security_deposit_settlements.contract_id', '=', 'contracts.id')
                ->join('properties', 'contracts.property_id', '=', 'properties.id')
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
                ->select(
                    'properties.id as property_id',
                    'properties.name as property_name',
                    'tenants.name as tenant_name',
                    'contracts.name as contract_name',
                    'security_deposit_settlements.original_deposit_amount as deposit_received',
                    'security_deposit_settlements.deduction_amount as deduction_amount',
                    'security_deposit_settlements.return_amount as deposit_refunded',
                    'security_deposit_settlements.return_date'
                )
                ->where(function ($q) use ($searchTerm) {
                    $q->where('properties.name', 'like', $searchTerm)
                        ->orWhere('tenants.name', 'like', $searchTerm)
                        ->orWhere('contracts.name', 'like', $searchTerm);
                })
                ->orderBy('properties.name');

            if ($this->startDate) {
                $query->whereDate('security_deposit_settlements.return_date', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('security_deposit_settlements.return_date', '<=', $this->endDate);
            }

            $reportDataCollection = $query->get()
                ->map(function ($item) {
                    $item->net_held = $item->deposit_received - $item->deposit_refunded;
                    return $item;
                });

            $data['reportData'] = $reportDataCollection;
            $data['grandTotals'] = [
                'contracts' => $reportDataCollection->count(),
                'received' => $reportDataCollection->sum('deposit_received'),
                'deductions' => $reportDataCollection->sum('deduction_amount'),
                'refunded' => $reportDataCollection->sum('deposit_refunded'),
                'net_held' => $reportDataCollection->sum('net_held'),
            ];
        } elseif ($this->filter === 'unsettled') {
            $data['columns'] = ['#', 'Property Name', 'Contract #', 'Tenant Name', 'Deposit Received'];

            $query = Contract::with(['property', 'tenant'])
                ->whereHas('receipts', function ($q) {
                    $q->where('receipt_category', 'SECURITY_DEPOSIT');
                })
                ->whereDoesntHave('settlement')
                ->where('validity', 'YES')
                ->where(function ($q) use ($searchTerm) {
                    $q->whereHas('property', fn($subQ) => $subQ->where('name', 'like', $searchTerm))
                        ->orWhereHas('tenant', fn($subQ) => $subQ->where('name', 'like', $searchTerm))
                        ->orWhere('contracts.name', 'like', $searchTerm);
                })
                ->select('contracts.*')
                ->addSelect(DB::raw('(SELECT SUM(amount) FROM receipts WHERE receipts.contract_id = contracts.id AND receipts.receipt_category = \'SECURITY_DEPOSIT\') as total_deposit_received'))
                ->orderBy('contracts.property_id');

            $reportDataCollection = $query->get();
            $data['reportData'] = $reportDataCollection;
            $data['grandTotals'] = [
                'contracts' => $reportDataCollection->count(),
                'deposit_received' => $reportDataCollection->sum('total_deposit_received'),
            ];
        }
        return $data;
    }

    public function render()
    {
        $data = $this->getReportData();
        return view('livewire.reports.security-deposit-report', $data);
    }

    public function exportPdf()
    {
        $data = $this->getReportData();

        if ($data['reportData']->isEmpty()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No data available to export.']);
            return;
        }

        $pdf = Pdf::loadView('pdfs.reports.security-deposit', $data);
        $pdf->setPaper('a4', 'landscape'); // Landscape for wider tables

        $filename = 'security-deposit-report-' . $this->filter . '-' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function emailReport()
    {
        Log::debug('SecurityDepositReport: emailReport method entered.');

        // Get current filter state
        $currentFilter = $this->filter;
        $currentSearch = $this->search;
        $currentStartDate = $this->startDate;
        $currentEndDate = $this->endDate;
        $userId = Auth::id(); // Get user ID for the mailable

        // Basic check: Ensure user is logged in
        if (!$userId) {
            Log::warning('SecurityDepositReport: Cannot email report, user not authenticated.');
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Authentication required to email report.']);
            return;
        }
        $userEmail = Auth::user()->email;
        if (!$userEmail) {
            Log::warning('SecurityDepositReport: Cannot email report, user has no email address.', ['user_id' => $userId]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Your account does not have an email address configured.']);
            return;
        }

        // We no longer check for empty data here, the Mailable will handle it.
        // We no longer generate PDF here, the Mailable will handle it.

        // Send email job to the queue
        Log::debug('SecurityDepositReport: Attempting to dispatch email job to: ' . $userEmail, [
            'filter' => $currentFilter,
            'search' => $currentSearch,
            'startDate' => $currentStartDate,
            'endDate' => $currentEndDate,
            'userId' => $userId
        ]);

        try {
            Mail::to($userEmail)->send(new SecurityDepositReportMail(
                $currentFilter,
                $currentStartDate,
                $currentEndDate,
                $currentSearch,
                $userId
            ));
            Log::info('SecurityDepositReport: Email job dispatched successfully for: ' . $userEmail);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Report email queued successfully for ' . $userEmail]);
        } catch (\Exception $e) {
            Log::error("SecurityDepositReport: Email dispatch failed: " . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to queue email. Please check logs.']);
        }

        Log::debug('SecurityDepositReport: emailReport method finished.');
    }
}
