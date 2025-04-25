<?php

namespace App\Livewire\Settlements;

use App\Models\Contract;
use App\Models\Receipt;
use App\Models\SecurityDepositSettlement as Settlement;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SecurityDepositSettlement extends Component
{
    public Contract $contract;
    public $originalDepositAmount = 0;
    public $settlementExists = false;
    public $existingSettlement;

    // Form fields
    public $deduction_amount = 0;
    public $deduction_reason;
    public $return_date;
    public $return_payment_type = 'CASH';
    public $return_reference;
    public $notes;

    // Calculated property
    public $return_amount = 0;

    protected function rules()
    {
        $rules = [
            // originalDepositAmount is calculated, not validated here
            'deduction_amount' => 'required|numeric|min:0',
            'deduction_reason' => 'required_if:deduction_amount,>,0|nullable|string|max:1000',
            'return_date' => 'required|date',
            'return_payment_type' => 'required|in:CASH,CHEQUE,ONLINE_TRANSFER',
            'return_reference' => 'required_if:return_payment_type,CHEQUE,ONLINE_TRANSFER|nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ];

        // Validate deduction doesn't exceed original deposit
        $rules['deduction_amount'] .= '|max:' . $this->originalDepositAmount;

        return $rules;
    }

    protected $messages = [
        'deduction_reason.required_if' => 'Reason is required when deduction amount is greater than 0.',
        'return_reference.required_if' => 'Reference (Cheque No/Transaction ID) is required for Cheque or Online Transfer.',
        'deduction_amount.max' => 'Deduction amount cannot exceed the original deposit amount.',
    ];

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
        $this->return_date = now()->format('Y-m-d');

        // Check if settlement already exists
        $this->existingSettlement = Settlement::where('contract_id', $this->contract->id)->first();
        if ($this->existingSettlement) {
            $this->settlementExists = true;
            // Pre-fill form if settlement exists (read-only mode basically)
            $this->originalDepositAmount = $this->existingSettlement->original_deposit_amount;
            $this->deduction_amount = $this->existingSettlement->deduction_amount;
            $this->deduction_reason = $this->existingSettlement->deduction_reason;
            $this->return_amount = $this->existingSettlement->return_amount;
            $this->return_date = $this->existingSettlement->return_date->format('Y-m-d');
            $this->return_payment_type = $this->existingSettlement->return_payment_type;
            $this->return_reference = $this->existingSettlement->return_reference;
            $this->notes = $this->existingSettlement->notes;
        } else {
            // Calculate original deposit from receipts if no settlement exists
            $this->originalDepositAmount = Receipt::where('contract_id', $this->contract->id)
                ->where('receipt_category', 'SECURITY_DEPOSIT')
                // ->where('status', 'CLEARED') // Optionally only count cleared deposits
                ->sum('amount');

            // Initial calculation for return amount
            $this->calculateReturnAmount();
        }
    }

    // Updated hook to recalculate when deduction_amount changes
    public function updatedDeductionAmount($value)
    {
        // Ensure value is numeric, default to 0 if not
        $this->deduction_amount = is_numeric($value) ? floatval($value) : 0;
        $this->calculateReturnAmount();
    }

    public function calculateReturnAmount()
    {
        $deduction = is_numeric($this->deduction_amount) ? floatval($this->deduction_amount) : 0;
        if ($deduction < 0) $deduction = 0;

        $this->return_amount = max(0, $this->originalDepositAmount - $deduction);
    }

    public function saveSettlement()
    {
        if ($this->settlementExists) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Settlement already exists for this contract.']);
            return;
        }

        // Recalculate before validation
        $this->calculateReturnAmount();
        $validated = $this->validate();

        try {
            DB::beginTransaction();

            Settlement::create([
                'contract_id' => $this->contract->id,
                'original_deposit_amount' => $this->originalDepositAmount,
                'deduction_amount' => $validated['deduction_amount'],
                'deduction_reason' => $validated['deduction_reason'],
                'return_amount' => $this->return_amount, // Use calculated property
                'return_date' => $validated['return_date'],
                'return_payment_type' => $validated['return_payment_type'],
                'return_reference' => $validated['return_reference'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Security deposit settled successfully!']);
            // Refresh component state or redirect
            // $this->mount($this->contract); // Refresh
            return redirect()->route('contracts.show', $this->contract);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving settlement: ' . $e->getMessage(), ['contract_id' => $this->contract->id]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error saving settlement: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.settlements.security-deposit-settlement');
    }
}
