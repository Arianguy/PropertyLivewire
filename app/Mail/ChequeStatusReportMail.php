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
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ChequeStatusReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $reportType; // 'cleared' or 'upcoming'
    public ?string $startDate;
    public ?string $endDate;
    public string $search;
    public ?int $userId;

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
            subject: 'Cheque Status Report - ' . ucfirst($this->reportType),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // No complex data needed for the simple email body
        return new Content(
            view: 'emails.reports.cheque-status', // Use the specific email view
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
        Log::debug('ChequeStatusReportMail: Generating PDF attachment inside Mailable.', [
            'type' => $this->reportType,
            'search' => $this->search,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'userId' => $this->userId
        ]);

        try {
            // --- Replicate data fetching logic --- START ---
            $reportData = collect();
            $grandTotals = [];
            $columns = [];
            $searchTerm = '%' . $this->search . '%';
            $now = now();
            $userName = 'System';
            if ($this->userId) {
                $user = User::find($this->userId);
                $userName = $user ? $user->name : 'User Not Found';
            }

            $baseQuery = Receipt::with(['contract.property', 'contract.tenant'])
                ->where('payment_type', 'CHEQUE')
                ->join('contracts', 'receipts.contract_id', '=', 'contracts.id')
                ->join('properties', 'contracts.property_id', '=', 'properties.id')
                ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
                ->select(
                    'receipts.*',
                    'properties.name as property_name',
                    'tenants.name as tenant_name',
                    'contracts.name as contract_name'
                )
                ->where(function ($q) use ($searchTerm) {
                    $q->where('properties.name', 'like', $searchTerm)
                        ->orWhere('tenants.name', 'like', $searchTerm)
                        ->orWhere('contracts.name', 'like', $searchTerm)
                        ->orWhere('receipts.cheque_no', 'like', $searchTerm);
                });

            if ($this->reportType === 'cleared') {
                $columns = ['#', 'Property', 'Tenant', 'Contract #', 'Cheque #', 'Bank Name', 'Cleared On', 'Amount', 'Status'];
                $query = $baseQuery->where('receipts.status', 'CLEARED');
                if ($this->startDate) {
                    $query->whereDate('receipts.deposit_date', '>=', $this->startDate);
                }
                if ($this->endDate) {
                    $query->whereDate('receipts.deposit_date', '<=', $this->endDate);
                }
                $reportDataCollection = $query->orderBy('receipts.deposit_date', 'desc')->get();
            } elseif ($this->reportType === 'upcoming') {
                $columns = ['#', 'Property', 'Tenant', 'Contract #', 'Cheque #', 'Bank Name', 'Cheque Date', 'Amount', 'Status'];
                $query = $baseQuery->where('receipts.status', 'PENDING');
                if ($this->startDate) {
                    $query->whereDate('receipts.cheque_date', '>=', $this->startDate);
                }
                if ($this->endDate) {
                    $query->whereDate('receipts.cheque_date', '<=', $this->endDate);
                }
                $reportDataCollection = $query->orderBy('receipts.cheque_date', 'asc')->get();
            }

            if (!isset($reportDataCollection)) {
                Log::warning('ChequeStatusReportMail: Invalid report type encountered.', ['type' => $this->reportType]);
                return []; // Prevent errors if filter is somehow wrong
            }

            $reportData = $reportDataCollection;
            $grandTotals = [
                'cheques' => $reportDataCollection->count(),
                'amount' => $reportDataCollection->sum('amount'),
            ];
            // --- Replicate data fetching logic --- END ---

            if ($reportData->isEmpty()) {
                Log::warning('ChequeStatusReportMail: No data found, skipping attachment.');
                return [];
            }

            $pdfViewData = [
                'reportData' => $reportData,
                'grandTotals' => $grandTotals,
                'columns' => $columns,
                'currentFilter' => $this->reportType,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'generatedAt' => $now,
                'generatedBy' => $userName,
            ];

            $pdf = Pdf::loadView('pdfs.reports.cheque-status', $pdfViewData);
            $pdf->setPaper('a4', 'landscape');
            $pdfDataOutput = $pdf->output();
            $filename = 'cheque-status-report-' . $this->reportType . '-' . now()->format('Y-m-d') . '.pdf';

            Log::debug('ChequeStatusReportMail: PDF generated successfully.');

            return [
                Attachment::fromData(fn() => $pdfDataOutput, $filename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error("ChequeStatusReportMail: Error generating PDF attachment: " . $e->getMessage(), ['exception' => $e]);
            return [];
        }
    }
}
