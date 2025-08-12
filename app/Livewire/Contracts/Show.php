<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Receipt;
use Livewire\Component;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ContractReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    public Contract $contract;
    public $media = [];
    public $previousContracts = [];
    public $renewalContracts = [];

    public float $totalRentScheduled = 0;
    public float $totalRentCleared = 0;
    public float $totalRentPendingClearance = 0;
    public float $balanceDue = 0;

    public function mount(Contract $contract)
    {
        $this->contract = $contract->load(['receipts' => function ($query) {
            $query->select('id', 'contract_id', 'receipt_category', 'amount', 'status', 'payment_type', 'cheque_no', 'narration', 'receipt_date');
        }]);
        $this->loadMedia();
        $this->loadContractHistory();
        $this->calculateRentTotals();
    }

    public function calculateRentTotals()
    {
        $allReceipts = $this->contract->receipts ?? collect();
        $rentReceipts = $allReceipts->where('receipt_category', 'RENT');

        // 1. Collection Scheduled: Sum of RENT category receipts
        $this->totalRentScheduled = $rentReceipts->sum('amount');

        // 2. Unscheduled: Contract Amount - Collection Scheduled
        $this->balanceDue = max(0, $this->contract->amount - $this->totalRentScheduled);

        // 3. Realized Amount: Sum of CLEARED RENT receipts + RETURN CHEQUE receipts
        $clearedRent = $rentReceipts->where('status', 'CLEARED')->sum('amount');
        $returnChequePayments = $allReceipts->where('receipt_category', 'RETURN CHEQUE')->sum('amount'); // Assuming RETURN CHEQUE category implies realized funds
        $this->totalRentCleared = $clearedRent + $returnChequePayments;

        // 4. Balance Pending Realization: Total Contract Amount - Realized Amount
        $this->totalRentPendingClearance = max(0, $this->contract->amount - $this->totalRentCleared);
    }

    public function isSecurityDepositCleared()
    {
        $allReceipts = $this->contract->receipts ?? collect();
        $securityDepositReceipts = $allReceipts->where('receipt_category', 'SECURITY_DEPOSIT');
        
        // Check if there are any cleared security deposit receipts
        $hasClearedReceipts = $securityDepositReceipts->where('status', 'CLEARED')->isNotEmpty();
        
        // Also check if there's a settlement record
        $hasSettlement = $this->contract->settlement !== null;
        
        return $hasClearedReceipts || $hasSettlement;
    }

    public function loadMedia()
    {
        $this->media = $this->contract->getMedia('contracts_copy')->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->file_name,
                'size' => $item->size,
                'type' => $item->mime_type,
                'url' => $item->getUrl(),
                'download_url' => $item->getUrl() . '/download',
                'thumbnail' => $item->hasGeneratedConversion('thumb')
                    ? $item->getUrl('thumb')
                    : null
            ];
        })->toArray();
    }

    public function loadContractHistory()
    {
        // Get all previous contracts (ancestors)
        $this->previousContracts = $this->contract->allAncestors()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'name' => $contract->name,
                    'tenant' => $contract->tenant->name,
                    'property' => $contract->property->name,
                    'start_date' => $contract->cstart->format('M d, Y'),
                    'end_date' => $contract->cend->format('M d, Y'),
                    'amount' => number_format($contract->amount, 2),
                    'security_deposit' => number_format($contract->sec_amt, 2),
                    'ejari' => $contract->ejari,
                    'type' => $contract->type,
                    'validity' => $contract->validity,
                ];
            })->toArray();

        // Get all renewal contracts
        $this->renewalContracts = $this->contract->allRenewals()
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'name' => $contract->name,
                    'tenant' => $contract->tenant->name,
                    'property' => $contract->property->name,
                    'start_date' => $contract->cstart->format('M d, Y'),
                    'end_date' => $contract->cend->format('M d, Y'),
                    'amount' => number_format($contract->amount, 2),
                    'security_deposit' => number_format($contract->sec_amt, 2),
                    'ejari' => $contract->ejari,
                    'type' => $contract->type,
                    'validity' => $contract->validity,
                ];
            })->toArray();
    }

    public function terminateContract()
    {
        // Terminate the contract
        $this->contract->update(['validity' => 'NO']);

        // Update the property's status to 'VACANT'
        $this->contract->property->update(['status' => 'VACANT']);

        // Reload the contract
        $this->contract = $this->contract->fresh(['tenant', 'property']);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contract terminated successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.contracts.show');
    }

    /**
     * Generates the PDF report data (used only for direct download now).
     */
    private function generatePdfData(): string
    {
        // Ensure fresh data is loaded including settlement
        $this->contract->loadMissing('tenant', 'property', 'receipts', 'settlement');
        $hasSettlement = $this->contract->settlement !== null;
        $settlementDate = $hasSettlement ? $this->contract->settlement->created_at : null;

        $initiatingUserName = Auth::user() ? Auth::user()->name : 'N/A'; // Get user name for direct download

        $data = [
            'contract' => $this->contract,
            'totalRentScheduled' => $this->totalRentScheduled,
            'userName' => $initiatingUserName, // Add user name for direct download
            'balanceDue' => $this->balanceDue,
            'totalRentCleared' => $this->totalRentCleared,
            'totalRentPendingClearance' => $this->totalRentPendingClearance,
            // Add settlement data for PDF view
            'settlement' => $this->contract->settlement, // Pass the whole object or null
        ];

        $pdf = Pdf::loadView('reports.contract-details', $data);
        return $pdf->output(); // Get PDF content as string
    }

    /**
     * Exports the contract details as a PDF download.
     */
    public function exportToPdf()
    {
        try {
            $pdfData = $this->generatePdfData();
            $filename = 'contract-report-' . $this->contract->name . '.pdf';

            return response()->streamDownload(
                fn() => print($pdfData),
                $filename
            );
        } catch (\Exception $e) {
            Log::error('Error exporting contract PDF: ' . $e->getMessage(), ['contract_id' => $this->contract->id]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not generate PDF report.']);
        }
    }

    /**
     * Emails the contract details PDF report to the logged-in user.
     */
    public function emailPdfReport()
    {
        // Note: PDF Generation is now done inside the Mailable job
        $user = Auth::user();
        if (!$user || !$user->email) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not determine recipient email address.']);
            return;
        }

        try {
            // Explicitly encode strings to UTF-8 to handle potential bad characters from DB
            $contractName = mb_convert_encoding($this->contract->name ?? '', 'UTF-8', 'UTF-8');
            $tenantName = mb_convert_encoding($this->contract->tenant->name ?? '', 'UTF-8', 'UTF-8');
            $propertyName = mb_convert_encoding($this->contract->property->name ?? '', 'UTF-8', 'UTF-8');
            $initiatingUserId = Auth::id(); // Get user ID

            // Pass Contract ID and calculated totals (primitives) to the Mailable
            Mail::to($user->email)->queue(new ContractReportMail(
                $this->contract->id,
                $contractName, // Use sanitized name
                $tenantName, // Use sanitized name
                $propertyName, // Use sanitized name
                $initiatingUserId, // Pass user ID
                $this->totalRentScheduled,
                $this->balanceDue,
                $this->totalRentCleared,
                $this->totalRentPendingClearance
            ));

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Contract report emailed successfully to ' . $user->email]);
        } catch (\Exception $e) {
            // Log::error('Error queuing/preparing contract PDF email: ' . $e->getMessage() . ' Stack: ' . $e->getTraceAsString(), ['contract_id' => $this->contract->id ?? null]);
            // Use the more specific log call below
            Log::error('Error emailing contract PDF: ' . $e->getMessage(), ['contract_id' => $this->contract->id]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not email PDF report.']);
        }
    }

    /**
     * Closes the contract (sets validity to NO, type to Closed, property to VACANT).
     */
    public function closeContract()
    {
        // Basic check - perhaps add more robust permission checks later if needed
        if ($this->contract->validity !== 'YES') {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Contract is already inactive.']);
            return;
        }

        try {
            DB::beginTransaction();

            // Update the contract
            $this->contract->update([
                'validity' => 'NO',
                'type' => 'Closed', // Set type to Closed
            ]);

            // Update the property status
            $this->contract->property()->update(['status' => 'VACANT']);

            DB::commit();

            // Reload the contract data to reflect changes
            $this->contract->refresh();
            $this->calculateRentTotals(); // Recalculate totals if needed

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Contract closed successfully.']);

            // Optional: Redirect or just refresh component state
            // return redirect()->route('contracts.show', $this->contract);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error closing contract: ' . $e->getMessage(), ['contract_id' => $this->contract->id]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error closing contract.']);
        }
    }
}
