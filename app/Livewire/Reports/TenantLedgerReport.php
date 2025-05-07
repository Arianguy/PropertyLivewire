<?php

namespace App\Livewire\Reports;

use App\Models\Contract;
use Livewire\Component;
use Illuminate\Support\Facades\Auth; // For footer user name
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // For more complex validation if needed later
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
// Assume Mailable will be created at App\Mail\TenantLedgerReportMail
use App\Mail\TenantLedgerReportMail;

class TenantLedgerReport extends Component
{
    public $contractsWithDetails;
    public string $userName;

    public string $contractStatusFilter = 'active'; // Default to active
    public string $searchTerm = '';
    public ?string $startDate = null;
    public ?string $endDate = null;

    // Modal Properties
    public bool $showAttachmentModal = false;
    public ?string $modalAttachmentUrl = null;

    // For more complex validation, define rules here
    // protected function rules()
    // {
    //     return [
    //         'startDate' => 'nullable|date',
    //         'endDate' => 'nullable|date|after_or_equal:startDate',
    //     ];
    // }

    public function mount()
    {
        $this->userName = Auth::user() ? Auth::user()->name : 'System User';
        // Initial data load is handled by render calling loadReportData implicitly through contractsWithDetails getter or direct call
    }

    // This method name is a bit generic, let's rename to avoid potential future conflicts if we add more filtering
    public function setContractFilter(string $status): void
    {
        if (in_array($status, ['all', 'active', 'inactive'])) {
            $this->contractStatusFilter = $status;
            // Data will reload on next render due to property change
        }
    }

    public function clearDateFilters(): void
    {
        $this->startDate = null;
        $this->endDate = null;
    }

    // Optional: if you want to validate dates as they are updated
    // public function updatedStartDate($value) { $this->validateOnly('startDate'); }
    // public function updatedEndDate($value) { $this->validateOnly('endDate'); }

    public function loadReportData()
    {
        try {
            $query = Contract::with([
                'tenant',
                'receipts' => function ($receiptQuery) {
                    // Base order for receipts
                    $receiptQuery->orderBy('receipt_date', 'asc')->orderBy('id', 'asc');

                    // Apply date range filter to receipts
                    if ($this->startDate && $this->endDate) {
                        $receiptQuery->whereBetween('receipt_date', [$this->startDate, $this->endDate]);
                    } elseif ($this->startDate) {
                        $receiptQuery->where('receipt_date', '>=', $this->startDate);
                    } elseif ($this->endDate) {
                        $receiptQuery->where('receipt_date', '<=', $this->endDate);
                    }
                }
            ]);

            // Apply contract status filter
            if ($this->contractStatusFilter === 'active') {
                $query->where('validity', 'YES');
            } elseif ($this->contractStatusFilter === 'inactive') {
                $query->where('validity', 'NO');
            }

            // Apply search term filter to contracts
            if (!empty($this->searchTerm)) {
                $search = '%' . $this->searchTerm . '%';
                $query->where(function ($q) use ($search) {
                    $q->whereHas('tenant', function ($tenantQuery) use ($search) {
                        $tenantQuery->where('name', 'like', $search);
                    })
                        ->orWhere('contracts.id', 'like', $search)
                        ->orWhere('contracts.name', 'like', $search);
                });
            }

            // Get the filtered contracts with their filtered receipts
            $this->contractsWithDetails = $query->orderBy('id', 'desc')->get();

            // If date filters are active, filter out contracts that have NO receipts matching the date range
            if ($this->startDate || $this->endDate) {
                $this->contractsWithDetails = $this->contractsWithDetails->filter(function ($contract) {
                    return $contract->receipts->isNotEmpty();
                });
            }
        } catch (\Exception $e) {
            Log::error('Error loading Tenant Ledger Report data: ' . $e->getMessage(), ['exception' => $e]);
            $this->contractsWithDetails = collect();
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error loading report data. Please check logs.']);
        }
    }

    // Method to prepare data for PDF/Email (reusable)
    private function getReportRenderData(): array
    {
        // Ensure data is loaded/updated based on current filters
        $this->loadReportData();
        return [
            'contracts' => $this->contractsWithDetails,
            'reportGeneratedBy' => Auth::user() ? Auth::user()->name : 'System User',
            'filterStartDate' => $this->startDate,
            'filterEndDate' => $this->endDate,
            'filterStatus' => $this->contractStatusFilter,
            'filterSearchTerm' => $this->searchTerm,
            'isSingleContractReport' => $this->contractsWithDetails->count() === 1 && !empty($this->searchTerm),
            'generationDate' => now()->format('d-M-Y H:i:s'),
        ];
    }

    public function exportPdf()
    {
        $data = $this->getReportRenderData();

        if ($data['contracts']->isEmpty()) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'No data to export based on current filters.']);
            return;
        }

        try {
            // Pass the necessary data to the PDF view
            $pdf = Pdf::loadView('pdfs.reports.tenant-ledger-print', $data)
                ->setPaper('a4', 'landscape'); // Changed to landscape

            $filename = 'tenant-ledger-' . now()->format('YmdHis') . '.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);
        } catch (\Exception $e) {
            Log::error('Error generating Tenant Ledger PDF: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not generate PDF. Please check logs.']);
        }
    }

    public function emailReport()
    {
        $user = Auth::user();
        if (!$user || !$user->email) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Cannot email report. User email not found.']);
            return;
        }

        // Reuse getReportRenderData to ensure consistency
        $data = $this->getReportRenderData();

        if ($data['contracts']->isEmpty()) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'No data to email based on current filters.']);
            return;
        }

        try {
            // Pass necessary primitive data or identifiers to the Mailable
            // Avoid passing large collections directly if using queues
            // For simplicity here, passing filtered data (assuming direct dispatch or small reports)
            Mail::to($user->email)->queue(new TenantLedgerReportMail($data));

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Tenant Ledger report email queued successfully.']);
        } catch (\Exception $e) {
            Log::error('Error emailing Tenant Ledger Report: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not email report. Please check logs.']);
        }
    }

    // Modal Methods
    public function openAttachmentModal(string $url)
    {
        // Basic validation/check if URL is somewhat valid (optional)
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $this->modalAttachmentUrl = $url;
            $this->showAttachmentModal = true;
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Invalid attachment URL.']);
        }
    }

    public function closeAttachmentModal()
    {
        $this->showAttachmentModal = false;
        $this->modalAttachmentUrl = null;
    }

    public function render()
    {
        // $this->validate(); // Call validate if rules() method is defined and active
        $this->loadReportData();

        return view('livewire.reports.tenant-ledger-report', [
            'contracts' => $this->contractsWithDetails,
            'currentContractFilter' => $this->contractStatusFilter // Pass current filter to view for button styling
        ]);
    }
}
