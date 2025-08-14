<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertySale extends Component
{
    public Property $property;
    
    #[Validate('required|date|before_or_equal:today')]
    public $sale_date;
    
    #[Validate('required|numeric|min:1')]
    public $sale_price;
    
    #[Validate('required|string|max:255')]
    public $buyer_name;
    
    #[Validate('nullable|string|max:1000')]
    public $sale_notes;
    
    public $showConfirmation = false;
    public $saleConfirmed = false;
    
    public function mount(Property $property)
    {
        $this->property = $property;
        $this->sale_date = now()->format('Y-m-d');
    }
    
    public function showSaleConfirmation()
    {
        $this->validate();
        $this->showConfirmation = true;
    }
    
    public function cancelSale()
    {
        $this->showConfirmation = false;
    }
    
    public function confirmSale()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                $saleDate = Carbon::parse($this->sale_date);
                $contractClosureResults = [];
                
                // Process active contracts
                $activeContracts = $this->property->contracts()
                    ->where('validity', 'YES')
                    ->with(['receipts', 'tenant'])
                    ->get();
                
                foreach ($activeContracts as $contract) {
                    $contractResult = $this->processContractClosure($contract, $saleDate);
                    $contractClosureResults[] = $contractResult;
                }
                
                // Update property with sale information
                $this->property->update([
                    'status' => 'SOLD',
                    'sale_date' => $this->sale_date,
                    'sale_price' => $this->sale_price,
                    'buyer_name' => $this->buyer_name,
                    'sale_notes' => $this->sale_notes,
                    'is_archived' => true,
                    'archived_at' => now(),
                    'archived_by' => Auth::id(),
                ]);
                
                // Store contract closure results in session for display
                session()->put('contract_closures', $contractClosureResults);
            });
            
            $this->saleConfirmed = true;
            $this->showConfirmation = false;
            
            session()->flash('success', 'Property has been successfully sold and archived. Contract closures have been processed.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while processing the sale: ' . $e->getMessage());
        }
    }
    
    /**
     * Process contract closure with precise rent calculation and PDC refunds
     */
    private function processContractClosure($contract, $saleDate)
    {
        $contractStart = Carbon::parse($contract->cstart);
        $contractEnd = Carbon::parse($contract->cend);
        $totalContractDays = $contractStart->diffInDays($contractEnd) + 1;
        $dailyRent = $contract->amount / $totalContractDays;
        
        // Calculate rent for the period until sale date
        $daysUntilSale = $contractStart->diffInDays($saleDate) + 1;
        $rentDueUntilSale = $dailyRent * $daysUntilSale;
        
        // Get all payments made for this contract
        $totalPaid = $contract->receipts()
            ->whereIn('status', ['CLEARED', 'PENDING'])
            ->whereIn('receipt_category', ['RENT', 'ADVANCE_RENT'])
            ->sum('amount');
        
        // Calculate balance
        $finalBalance = $rentDueUntilSale - $totalPaid;
        
        // Process PDC refunds for future rent
        $pdcRefunds = $this->processPDCRefunds($contract, $saleDate, $dailyRent, $totalContractDays);
        
        // Close the contract
        $contract->update([
            'validity' => 'NO',
            'close_date' => $saleDate,
            'termination_reason' => 'Property Sold',
            'updated_at' => now(),
        ]);
        
        // Create refund receipts if necessary
        foreach ($pdcRefunds as $refund) {
            if ($refund['refund_amount'] > 0) {
                $this->createRefundReceipt($contract, $refund);
            }
        }
        
        return [
            'contract_id' => $contract->id,
            'tenant_name' => $contract->tenant->name,
            'contract_period' => $contractStart->format('M d, Y') . ' - ' . $contractEnd->format('M d, Y'),
            'sale_date' => $saleDate->format('M d, Y'),
            'total_contract_amount' => $contract->amount,
            'daily_rent' => round($dailyRent, 2),
            'days_until_sale' => $daysUntilSale,
            'rent_due_until_sale' => round($rentDueUntilSale, 2),
            'total_paid' => $totalPaid,
            'final_balance' => round($finalBalance, 2),
            'pdc_refunds' => $pdcRefunds,
            'total_refund_amount' => array_sum(array_column($pdcRefunds, 'refund_amount'))
        ];
    }
    
    /**
     * Process PDC refunds for excess rent collected
     */
    private function processPDCRefunds($contract, $saleDate, $dailyRent, $totalContractDays)
    {
        $refunds = [];
        
        // Get all pending PDCs (cheques that haven't been cleared yet)
        $pendingPDCs = $contract->receipts()
            ->where('payment_type', 'CHEQUE')
            ->where('status', 'PENDING')
            ->whereIn('receipt_category', ['RENT', 'ADVANCE_RENT'])
            ->where('cheque_date', '>', $saleDate)
            ->get();
        
        foreach ($pendingPDCs as $pdc) {
            $chequeDate = Carbon::parse($pdc->cheque_date);
            $daysAfterSale = $saleDate->diffInDays($chequeDate);
            
            // Calculate refund amount based on days after sale
            $refundAmount = min($pdc->amount, $dailyRent * $daysAfterSale);
            
            $refunds[] = [
                'receipt_id' => $pdc->id,
                'cheque_no' => $pdc->cheque_no,
                'cheque_date' => $chequeDate->format('M d, Y'),
                'original_amount' => $pdc->amount,
                'refund_amount' => round($refundAmount, 2),
                'reason' => 'Property sold - excess rent refund'
            ];
            
            // Update the PDC status to indicate refund processed
            $pdc->update([
                'status' => 'REFUNDED',
                'remarks' => 'Refunded due to property sale on ' . $saleDate->format('M d, Y')
            ]);
        }
        
        return $refunds;
    }
    
    /**
     * Create refund receipt for PDC refunds
     */
    private function createRefundReceipt($contract, $refundData)
    {
        $contract->receipts()->create([
            'receipt_category' => 'REFUND',
            'payment_type' => 'REFUND',
            'amount' => -$refundData['refund_amount'], // Negative amount for refund
            'receipt_date' => now(),
            'narration' => 'Refund for excess rent - Property sold',
            'cheque_no' => 'REF-' . $refundData['cheque_no'],
            'status' => 'PROCESSED',
            'remarks' => $refundData['reason'],
        ]);
    }

    public function render()
    {
        return view('livewire.property-sale');
    }
}