<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Keep Auth facade if needed for user info inside Job

class ContractReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $reportType; // 'ongoing' or 'closed'
    public ?string $startDate;
    public ?string $endDate;
    public string $search;
    public ?int $userId; // ID of the user who requested the report

    /**
     * Create a new message instance.
     */
    public function __construct(string $reportType, ?string $startDate, ?string $endDate, string $search, ?int $userId)
    {
        $this->reportType = $reportType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
        $this->userId = $userId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contracts Report - ' . ucfirst($this->reportType),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Simple view for the email body
        return new Content(
            view: 'emails.reports.contract-status', // We will create this view next
            with: [
                'reportType' => $this->reportType,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        Log::debug('ContractReportMail: Generating PDF attachment inside Mailable.', [
            'type' => $this->reportType,
            'search' => $this->search,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'userId' => $this->userId
        ]);

        try {
            // --- Replicate data fetching logic from ContractReport --- START ---
            $reportData = collect();
            $grandTotals = [];
            $columns = [];
            $searchTerm = '%' . $this->search . '%';
            $now = now();
            $userName = 'System'; // Default
            if ($this->userId) {
                $user = User::find($this->userId);
                $userName = $user ? $user->name : 'User ID: ' . $this->userId;
            }

            $baseQuery = Contract::with(['property', 'tenant'])
                ->join('properties', 'contracts.property_id', '=', 'properties.id')
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
                ->select(
                    'contracts.*',
                    'properties.name as property_name',
                    'tenants.name as tenant_name'
                )
                ->where(function ($q) use ($searchTerm) {
                    $q->where('properties.name', 'like', $searchTerm)
                        ->orWhere('tenants.name', 'like', $searchTerm)
                        ->orWhere('contracts.name', 'like', $searchTerm);
                });

            $columns = ['#', 'Property', 'Tenant', 'Contract #', 'Start Date', 'End Date', 'Amount', 'Status'];
            $query = null; // Initialize query

            if ($this->reportType === 'ongoing') {
                $query = $baseQuery->where('contracts.validity', 'YES');
                $columns[] = 'Remaining Days';
            } elseif ($this->reportType === 'closed') {
                $query = $baseQuery->where('contracts.validity', 'NO');
                $columns[] = 'Closed On';
            }

            if (!$query) { // Handle case where filter type might be invalid
                Log::warning('ContractReportMail: Invalid report type encountered.', ['type' => $this->reportType]);
                return [];
            }

            if ($this->startDate) {
                $query->whereDate('contracts.cstart', '>=', $this->startDate);
            }
            if ($this->endDate) {
                $query->whereDate('contracts.cstart', '<=', $this->endDate);
            }

            $reportDataCollection = $query->orderBy('contracts.cend', $this->reportType === 'ongoing' ? 'asc' : 'desc')
                ->get();

            $reportData = $reportDataCollection;
            $grandTotals = [
                'contracts' => $reportDataCollection->count(),
                'amount' => $reportDataCollection->sum('amount'),
            ];
            // --- Replicate data fetching logic --- END ---

            if ($reportData->isEmpty()) {
                Log::warning('ContractReportMail: No data found for PDF attachment, skipping.');
                return []; // Don't attach anything if no data
            }

            $pdfViewData = [
                'reportData' => $reportData,
                'grandTotals' => $grandTotals,
                'columns' => $columns,
                'currentFilter' => $this->reportType,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'generatedAt' => $now,
                'generatedBy' => $userName, // Use the fetched user name
            ];

            $pdf = Pdf::loadView('pdfs.reports.contract-status', $pdfViewData);
            $pdf->setPaper('a4', 'landscape');
            $pdfDataOutput = $pdf->output();

            $filename = 'contract-report-' . $this->reportType . '-' . now()->format('Y-m-d') . '.pdf';

            Log::info('ContractReportMail: PDF generated successfully for attachment.', ['filename' => $filename]);

            return [
                Attachment::fromData(fn() => $pdfDataOutput, $filename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error("ContractReportMail: Failed to generate PDF attachment: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString() // Log trace for detailed debugging
            ]);
            return []; // Return empty array if PDF generation fails
        }
    }
}
