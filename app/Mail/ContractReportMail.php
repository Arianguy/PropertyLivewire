<?php

namespace App\Mail;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ContractReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Store identifiers and necessary calculated data
    public int $contractId;
    public ?int $userId;
    public string $contractName;
    public string $tenantName;
    public string $propertyName;
    public float $totalRentScheduled;
    public float $balanceDue;
    public float $totalRentCleared;
    public float $totalRentPendingClearance;
    // Store fetched username for consistency
    private string $fetchedUserName = 'N/A';

    /**
     * Create a new message instance.
     */
    public function __construct(
        int $contractId,
        string $contractName,
        string $tenantName,
        string $propertyName,
        ?int $userId,
        float $totalRentScheduled,
        float $balanceDue,
        float $totalRentCleared,
        float $totalRentPendingClearance
    ) {
        $this->contractId = $contractId;
        $this->contractName = $contractName;
        $this->userId = $userId;
        $this->tenantName = $tenantName;
        $this->propertyName = $propertyName;
        $this->totalRentScheduled = $totalRentScheduled;
        $this->balanceDue = $balanceDue;
        $this->totalRentCleared = $totalRentCleared;
        $this->totalRentPendingClearance = $totalRentPendingClearance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contract Report - ' . $this->contractName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $timestamp = now()->format('Y-m-d H:i:s'); // Generate timestamp for email body
        return new Content(
            markdown: 'emails.contracts.report', // Simple markdown email view
            with: [
                'contractName' => $this->contractName,
                'tenantName' => $this->tenantName,
                'propertyName' => $this->propertyName,
                'userName' => $this->fetchedUserName, // Use the fetched name
                'timestamp' => $timestamp, // Pass timestamp
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
        Log::debug("Generating PDF attachment inside Mailable for Contract ID: {$this->contractId}");

        try {
            // Fetch the contract with necessary relations inside the job
            $contract = Contract::with('tenant', 'property', 'receipts')->find($this->contractId);

            if (!$contract) {
                Log::error("Could not find Contract {$this->contractId} inside ContractReportMail job.");
                return []; // Return empty if contract not found
            }

            // Fetch the user name based on stored ID
            if ($this->userId) {
                Log::debug("Fetching user name for User ID: {$this->userId} inside job.");
                $user = User::find($this->userId);
                if ($user && $user->name) {
                    $this->fetchedUserName = $user->name;
                    Log::debug("User found: {$this->fetchedUserName}");
                } else {
                    $this->fetchedUserName = 'User Not Found';
                    Log::warning("User or user name not found for ID: {$this->userId}");
                }
            } else {
                $this->fetchedUserName = 'N/A (No User ID provided)';
                Log::warning("No User ID provided to ContractReportMail job.");
            }

            // Prepare data for the PDF view
            $data = [
                'contract' => $contract,
                'userName' => $this->fetchedUserName, // Pass fetched user name
                'totalRentScheduled' => $this->totalRentScheduled,
                'balanceDue' => $this->balanceDue,
                'totalRentCleared' => $this->totalRentCleared,
                'totalRentPendingClearance' => $this->totalRentPendingClearance,
            ];

            $pdf = Pdf::loadView('reports.contract-details', $data);
            $pdfData = $pdf->output();
            // Use fetched contract name for filename, just in case property was weird
            $pdfFilename = 'contract-report-' . $contract->name . '.pdf';
            // Sanitize filename
            $safeFilename = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $pdfFilename);

            Log::debug("PDF generated successfully for Contract ID: {$this->contractId}, filename: {$safeFilename}");

            return [
                Attachment::fromData(fn() => $pdfData, $safeFilename)
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error("Error generating PDF attachment in ContractReportMail for Contract ID: {$this->contractId}. Error: " . $e->getMessage());
            return []; // Return empty array on error
        }
    }
}
