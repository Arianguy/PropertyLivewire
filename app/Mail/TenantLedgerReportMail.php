<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class TenantLedgerReportMail extends Mailable implements ShouldQueue // Implement ShouldQueue for background sending
{
    use Queueable, SerializesModels;

    public array $reportData;

    /**
     * Create a new message instance.
     */
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Tenant Ledger Report';
        if ($this->reportData['isSingleContractReport'] ?? false) {
            // Try to get contract name/id for subject if it's a single report
            $contractName = $this->reportData['contracts']->first()->name ?? 'Specific Contract';
            $subject = 'Tenant Ledger Report for Contract: ' . $contractName;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.tenant-ledger',
            with: [
                'reportDate' => $this->reportData['generationDate'] ?? now()->format('d-M-Y'),
                'filterInfo' => $this->reportData, // Pass relevant filter info if needed in email body
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
        try {
            // Generate PDF content using the same view and data as the direct download
            $pdf = Pdf::loadView('pdfs.reports.tenant-ledger-print', $this->reportData)
                ->setPaper('a4', 'landscape');

            $filename = 'tenant-ledger-' . now()->format('YmdHis') . '.pdf';

            return [
                Attachment::fromData(fn() => $pdf->output(), $filename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error('Error generating PDF for Tenant Ledger email attachment: ' . $e->getMessage(), ['exception' => $e]);
            // Optionally notify sender or admin
            return []; // Return empty array if PDF generation fails
        }
    }
}
