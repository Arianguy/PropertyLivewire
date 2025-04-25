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
use App\Models\SecurityDepositSettlement as Settlement;

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
        $timestamp = now()->format('Y-m-d H:i:s');

        // Fetch required data within the job for the email body
        $contract = Contract::with('receipts', 'settlement')->find($this->contractId);
        if (!$contract) {
            Log::error("Could not find Contract {$this->contractId} inside ContractReportMail content generation.");
            // Return empty content or fallback view?
            // For now, we'll proceed but data might be missing in the view.
            $contract = new Contract(); // Avoid errors in view, but data will be wrong
        }
        $settlement = $contract->settlement; // Get settlement from loaded relationship

        // Fetch user name specifically for email body content
        $userNameForBody = 'N/A';
        if ($this->userId) {
            $user = User::find($this->userId);
            $userNameForBody = $user ? $user->name : 'User Not Found';
        }

        return new Content(
            view: 'emails.contracts.report-html', // Use HTML view
            with: [
                'contractName' => $this->contractName,
                'tenantName' => $this->tenantName,
                'propertyName' => $this->propertyName,
                'userName' => $userNameForBody, // Use name fetched for body
                'timestamp' => $timestamp,
                // Pass full objects/collections needed by the HTML view
                'contractType' => $contract->type ?? null,
                'contractStatus' => $contract->validity === 'YES' ? 'Active' : 'Inactive',
                'terminationReason' => $contract->termination_reason ?? null,
                'settlement' => $settlement, // Pass the settlement object (or null)
                'receipts' => $contract->receipts ?? collect(), // Pass receipts collection
                // Pass calculated totals as well
                'totalRentScheduled' => $this->totalRentScheduled,
                'balanceDue' => $this->balanceDue,
                'totalRentCleared' => $this->totalRentCleared,
                'totalRentPendingClearance' => $this->totalRentPendingClearance,
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
            // Fetch the contract with necessary relations AND settlement inside the job
            $contract = Contract::with('tenant', 'property', 'receipts', 'settlement')->find($this->contractId);

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
                // Pass the settlement object itself (or null)
                'settlement' => $contract->settlement,
            ];

            // Debugging: Check the settlement data before passing to view
            Log::debug("Data being passed to PDF view in ContractReportMail:", [
                'contract_id' => $this->contractId,
                'settlement_type' => gettype($contract->settlement),
                'settlement_is_null' => is_null($contract->settlement),
                'settlement_data' => $contract->settlement ? $contract->settlement->toArray() : null // Log data if not null
            ]);

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
