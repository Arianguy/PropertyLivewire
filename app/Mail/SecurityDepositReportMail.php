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
use App\Models\SecurityDepositSettlement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Keep Auth for fetching user name

class SecurityDepositReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Keep only parameters needed to regenerate the report
    // public string $pdfData; // REMOVED
    // public string $filename; // REMOVED - will generate inside
    public string $reportType; // Keep (filter)
    public ?string $startDate;
    public ?string $endDate;
    public string $search;
    public ?int $userId; // To fetch user name inside job

    /**
     * Create a new message instance.
     */
    public function __construct(string $reportType, ?string $startDate, ?string $endDate, string $search, ?int $userId)
    {
        // $this->pdfData = $pdfData; // REMOVED
        // $this->filename = $filename; // REMOVED
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
            subject: 'Security Deposit Report - ' . ucfirst($this->reportType),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.security-deposit',
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
        Log::debug('SecurityDepositReportMail: Generating PDF attachment inside Mailable.', [
            'type' => $this->reportType,
            'search' => $this->search,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'userId' => $this->userId
        ]);

        try {
            // --- Replicate data fetching logic from component --- START ---
            $reportData = collect();
            $grandTotals = [];
            $columns = [];
            $searchTerm = '%' . $this->search . '%';
            $now = now();
            $userName = 'System'; // Default user name
            if ($this->userId) {
                $user = User::find($this->userId);
                $userName = $user ? $user->name : 'User Not Found';
            }

            if ($this->reportType === 'settled') {
                $columns = [
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

                $reportData = $reportDataCollection;
                $grandTotals = [
                    'contracts' => $reportDataCollection->count(),
                    'received' => $reportDataCollection->sum('deposit_received'),
                    'deductions' => $reportDataCollection->sum('deduction_amount'),
                    'refunded' => $reportDataCollection->sum('deposit_refunded'),
                    'net_held' => $reportDataCollection->sum('net_held'),
                ];
            } elseif ($this->reportType === 'unsettled') {
                $columns = ['#', 'Property Name', 'Contract #', 'Tenant Name', 'Deposit Received'];

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
                $reportData = $reportDataCollection;
                $grandTotals = [
                    'contracts' => $reportDataCollection->count(),
                    'deposit_received' => $reportDataCollection->sum('total_deposit_received'),
                ];
            }
            // --- Replicate data fetching logic from component --- END ---

            if ($reportData->isEmpty()) {
                Log::warning('SecurityDepositReportMail: No data found for report, skipping attachment generation.');
                return []; // Don't attach if no data
            }

            // Prepare data for PDF view
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

            // Generate PDF
            $pdf = Pdf::loadView('pdfs.reports.security-deposit', $pdfViewData);
            $pdf->setPaper('a4', 'landscape');
            $pdfDataOutput = $pdf->output();
            $filename = 'security-deposit-report-' . $this->reportType . '-' . now()->format('Y-m-d') . '.pdf';

            Log::debug('SecurityDepositReportMail: PDF generated successfully for email attachment.');

            return [
                Attachment::fromData(fn() => $pdfDataOutput, $filename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error("SecurityDepositReportMail: Error generating PDF attachment: " . $e->getMessage(), ['exception' => $e]);
            return []; // Return empty array on error
        }
    }
}
