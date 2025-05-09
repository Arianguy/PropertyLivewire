<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PaymentReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Collection $payments;
    public ?string $search;
    public ?string $propertyName;
    public ?string $paymentTypeName;
    public ?string $startDate;
    public ?string $endDate;
    public string $sortBy;
    public string $sortDirection;
    public string $generatedBy;
    public string $generatedAtDisplay;
    public float $grandTotalAmount;
    public int $totalPaymentsCount;
    public string $pdfFilename;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Collection $payments,
        ?string $search,
        ?string $propertyName,
        ?string $paymentTypeName,
        ?string $startDate,
        ?string $endDate,
        string $sortBy,
        string $sortDirection,
        string $generatedBy,
        string $generatedAtDisplay,
        float $grandTotalAmount,
        int $totalPaymentsCount
    ) {
        $this->payments = $payments;
        $this->search = $search;
        $this->propertyName = $propertyName;
        $this->paymentTypeName = $paymentTypeName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->generatedBy = $generatedBy;
        $this->generatedAtDisplay = $generatedAtDisplay;
        $this->grandTotalAmount = $grandTotalAmount;
        $this->totalPaymentsCount = $totalPaymentsCount;
        $this->pdfFilename = 'payments-report-' . now()->format('Y-m-d-His') . '.pdf';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payments Report - ' . $this->generatedAtDisplay,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.payments-report',
            with: [
                'search' => $this->search,
                'propertyName' => $this->propertyName,
                'paymentTypeName' => $this->paymentTypeName,
                'startDate' => $this->startDate ? \Carbon\Carbon::parse($this->startDate)->format('d-M-Y') : null,
                'endDate' => $this->endDate ? \Carbon\Carbon::parse($this->endDate)->format('d-M-Y') : null,
                'generatedBy' => $this->generatedBy,
                'generatedAt' => $this->generatedAtDisplay,
                'grandTotalAmount' => $this->grandTotalAmount,
                'totalPaymentsCount' => $this->totalPaymentsCount,
                'pdfFilename' => $this->pdfFilename,
                'url' => route('payments.index')
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
            $pdfViewData = [
                'payments' => $this->payments,
                'search' => $this->search,
                'propertyId' => null,
                'paymentTypeId' => null,
                'propertyName' => $this->propertyName,
                'paymentTypeName' => $this->paymentTypeName,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'sortBy' => $this->sortBy,
                'sortDirection' => $this->sortDirection,
                'generatedAt' => now(),
                'generatedBy' => $this->generatedBy,
                'grandTotalAmount' => $this->grandTotalAmount,
                'totalPaymentsCount' => $this->totalPaymentsCount,
            ];

            $pdf = Pdf::loadView('pdfs.reports.payments-list', $pdfViewData)
                ->setPaper('a4', 'landscape');

            return [
                Attachment::fromData(fn() => $pdf->output(), $this->pdfFilename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error('Error generating PDF for Payment Report email attachment: ' . $e->getMessage(), ['exception' => $e, 'report_data' => $this->toArrayForLog()]);
            return [];
        }
    }

    protected function toArrayForLog(): array
    {
        return [
            'search' => $this->search,
            'propertyName' => $this->propertyName,
            'paymentTypeName' => $this->paymentTypeName,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'generatedBy' => $this->generatedBy,
            'totalPaymentsCount' => $this->totalPaymentsCount,
            'payments_count' => $this->payments->count()
        ];
    }
}
