<?php

namespace App\Livewire\Reports;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant; // Added Tenant model
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination; // Will add if needed later
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContractReportMail; // <-- Add this use statement
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;

class ContractReport extends Component
{
    // use WithPagination; // Keep commented for now

    public string $filter = 'ongoing'; // Default to ongoing contracts
    public string $search = '';
    public ?string $startDate = null; // Optional: Filter by start date range
    public ?string $endDate = null;   // Optional: Filter by end date range

    protected $rules = [
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
    ];

    // Reset pagination hooks (uncomment if using pagination)
    // public function updatingSearch() { $this->resetPage(); }
    // public function updatingFilter() { $this->resetPage(); }
    // public function updatingStartDate() { $this->resetPage(); }
    // public function updatingEndDate() { $this->resetPage(); }


    public function setFilter(string $status): void
    {
        if (in_array($status, ['ongoing', 'closed'])) {
            $this->filter = $status;
            // $this->resetPage(); // Uncomment if using pagination
        }
    }

    public function clearDates(): void
    {
        $this->startDate = null;
        $this->endDate = null;
        // $this->resetPage(); // Uncomment if using pagination
    }

    private function getReportData(): array
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

        // Base query for contracts with relationships
        $baseQuery = Contract::with(['property', 'tenant'])
            ->join('properties', 'contracts.property_id', '=', 'properties.id')
            ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
            ->select(
                'contracts.*', // Select all contract fields
                'properties.name as property_name',
                'tenants.name as tenant_name'
                // Add other relevant fields if needed
            )
            ->where(function ($q) use ($searchTerm) {
                $q->where('properties.name', 'like', $searchTerm)
                    ->orWhere('tenants.name', 'like', $searchTerm)
                    ->orWhere('contracts.name', 'like', $searchTerm); // Search contract name/number
                // Add other searchable fields like contract amount, etc.
            });

        // Define columns based on filter
        $data['columns'] = ['#', 'Property', 'Tenant', 'Contract #', 'Start Date', 'End Date', 'Amount', 'Status']; // Common columns

        if ($this->filter === 'ongoing') {
            // Modify query for ongoing contracts (cend >= today)
            $query = $baseQuery->where('contracts.cend', '>=', $now->toDateString());
            $data['columns'][] = 'Remaining Days'; // Add specific column for ongoing

        } elseif ($this->filter === 'closed') {
            // Modify query for closed contracts (cend < today)
            $query = $baseQuery->where('contracts.cend', '<', $now->toDateString());
            $data['columns'][] = 'Closed On'; // Add specific column for closed
        } else {
            $query = $baseQuery; // Fallback or handle error
        }


        // Apply date filters (on contract cstart)
        if ($this->startDate) {
            $query->whereDate('contracts.cstart', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('contracts.cstart', '<=', $this->endDate);
        }

        // Order results by end date
        $reportDataCollection = $query->orderBy('contracts.cend', $this->filter === 'ongoing' ? 'asc' : 'desc')
            ->get();

        $data['reportData'] = $reportDataCollection;
        $data['grandTotals'] = [
            'contracts' => $reportDataCollection->count(),
            'amount' => $reportDataCollection->sum('amount'), // Use the correct amount field
        ];

        return $data;
    }

    public function render()
    {
        try {
            $data = $this->getReportData();
            return view('livewire.reports.contract-report', $data);
        } catch (\Exception $e) {
            Log::error('Error rendering Contract Report: ' . $e->getMessage());
            // Optionally dispatch a notification to the user
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error loading report data. Please try again or contact support.']);
            // Return a view with an error message or empty state
            return view('livewire.reports.contract-report-error'); // Create this view
        }
    }

    public function exportPdf()
    {
        $data = $this->getReportData();

        if ($data['reportData']->isEmpty()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No data available to export.']);
            return;
        }

        try {
            $pdf = Pdf::loadView('pdfs.reports.contract-status', $data);
            $pdf->setPaper('a4', 'landscape'); // Or 'portrait' if preferred

            $filename = 'contract-report-' . $this->filter . '-' . now()->format('Y-m-d') . '.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            Log::error("ContractReport: PDF Export failed: " . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to generate PDF. Please check logs.']);
        }
    }

    public function emailReport()
    {
        Log::debug('ContractReport: emailReport method entered.');
        $userId = Auth::id();
        if (!$userId) {
            Log::warning('ContractReport: Cannot email report, user not authenticated.');
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Authentication required to email report.']);
            return;
        }
        $user = Auth::user();
        $userEmail = $user->email;
        if (!$userEmail) {
            Log::warning('ContractReport: Cannot email report, user has no email address.', ['user_id' => $userId]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Your account does not have an email address configured.']);
            return;
        }

        Log::debug('ContractReport: Attempting to dispatch email job to: ' . $userEmail, [
            'filter' => $this->filter,
            'search' => $this->search,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'userId' => $userId
        ]);

        try {
            Mail::to($userEmail)->send(new ContractReportMail(
                $this->filter,
                $this->startDate,
                $this->endDate,
                $this->search,
                $userId
            ));
            Log::info('ContractReport: Email job dispatched successfully for: ' . $userEmail);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Report email queued successfully for ' . $userEmail]);
        } catch (\Exception $e) {
            Log::error("ContractReport: Email dispatch failed: " . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to queue email. Please check logs.']);
        }

        Log::debug('ContractReport: emailReport method finished.');
    }

    // Removed viewContractPdf method and related listeners as modal is removed
}
