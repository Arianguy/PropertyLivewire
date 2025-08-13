<?php

namespace App\Livewire\Receipts;

use Livewire\Component;
use App\Models\Receipt;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $receipts = [];
    public $contract_id;
    public $contract;

    public function mount($contract)
    {
        if (is_numeric($contract)) {
            $this->contract = Contract::with('tenant')->findOrFail($contract);
        } else {
            $this->contract = $contract;
        }
        $this->contract_id = $this->contract->id;

        // Initialize receipts array with one empty receipt
        $this->receipts = [[
            'receipt_category' => 'RENT',
            'payment_type' => 'CASH',
            'amount' => '',
            'receipt_date' => now()->format('Y-m-d'),
            'narration' => '',
            'cheque_no' => '',
            'cheque_bank' => '',
            'cheque_date' => '',
            'transaction_reference' => '',
            'cheque_image' => null,
            'transfer_receipt_image' => null,
            'vat_rate' => $this->contract->isVatApplicable() ? $this->contract->getVatRate() : 0,
            'vat_amount' => 0,
            'vat_inclusive' => false,
        ]];
    }

    public function addReceipt()
    {
        $this->receipts[] = [
            'receipt_category' => 'RENT',
            'payment_type' => 'CASH',
            'amount' => '',
            'receipt_date' => now()->format('Y-m-d'),
            'narration' => '',
            'cheque_no' => '',
            'cheque_bank' => '',
            'cheque_date' => '',
            'transaction_reference' => '',
            'cheque_image' => null,
            'transfer_receipt_image' => null,
            'vat_rate' => $this->contract->isVatApplicable() ? $this->contract->getVatRate() : 0,
            'vat_amount' => 0,
            'vat_inclusive' => false,
        ];
    }

    public function removeReceipt($index)
    {
        unset($this->receipts[$index]);
        $this->receipts = array_values($this->receipts);
    }

    public function calculateVat($index)
    {
        if (!$this->contract->isVatApplicable() || !isset($this->receipts[$index])) {
            return;
        }

        $receipt = &$this->receipts[$index];
        $category = $receipt['receipt_category'] ?? 'RENT';
        $amount = (float) ($receipt['amount'] ?? 0);
        $vatRate = (float) ($receipt['vat_rate'] ?? 0);
        $vatInclusive = $receipt['vat_inclusive'] ?? false;

        // For VAT category, the entire amount should be stored as vat_amount
        if ($category === 'VAT') {
            $receipt['vat_amount'] = $amount;
            $receipt['vat_rate'] = $this->contract->getVatRate();
            $receipt['vat_inclusive'] = false;
            // Set amount to 0 since this is purely VAT
            $receipt['amount'] = 0;
            return;
        }

        // For RENT category, VAT is always inclusive when paid together
        if ($category === 'RENT') {
            $receipt['vat_inclusive'] = true;
            $vatInclusive = true;
        }

        if ($amount > 0 && $vatRate > 0) {
            if ($vatInclusive) {
                // Amount includes VAT, calculate VAT amount
                $receipt['vat_amount'] = round($amount * ($vatRate / (100 + $vatRate)), 2);
            } else {
                // Amount excludes VAT, calculate VAT amount
                $receipt['vat_amount'] = round($amount * ($vatRate / 100), 2);
            }
        } else {
            $receipt['vat_amount'] = 0;
        }
    }

    public function updatedReceipts($value, $key)
    {
        // Extract index from key (e.g., "0.amount" -> 0)
        $parts = explode('.', $key);
        $index = (int) $parts[0];
        $field = $parts[1] ?? '';

        // Handle category change
        if ($field === 'receipt_category') {
            $receipt = &$this->receipts[$index];
            if ($value === 'VAT') {
                // For VAT category, move amount to vat_amount and reset amount
                $currentAmount = $receipt['amount'] ?? 0;
                $receipt['vat_amount'] = $currentAmount;
                $receipt['amount'] = 0;
                $receipt['vat_rate'] = $this->contract->getVatRate();
                $receipt['vat_inclusive'] = false;
            } elseif ($value === 'RENT' && $this->contract->isVatApplicable()) {
                // For RENT category, set default VAT rate and inclusive
                $receipt['vat_rate'] = $this->contract->getVatRate();
                $receipt['vat_inclusive'] = true;
                $this->calculateVat($index);
            }
        }

        // Recalculate VAT when amount, vat_rate, or vat_inclusive changes
        if (in_array($field, ['amount', 'vat_rate', 'vat_inclusive'])) {
            $this->calculateVat($index);
        }
    }

    protected function rules()
    {
        $rules = [];

        foreach ($this->receipts as $index => $receipt) {
            $rules["receipts.$index.receipt_category"] = 'required|in:SECURITY_DEPOSIT,RENT,RETURN CHEQUE,VAT,CANCELLED';
            $rules["receipts.$index.payment_type"] = 'required|in:CASH,CHEQUE,ONLINE_TRANSFER';
            
            // For VAT category, validate vat_amount instead of amount
            if (($receipt['receipt_category'] ?? 'RENT') === 'VAT') {
                $rules["receipts.$index.amount"] = 'nullable|numeric|min:0';
                $rules["receipts.$index.vat_amount"] = 'required|numeric|min:0.01';
            } else {
                $rules["receipts.$index.amount"] = 'required|numeric|min:0.01';
            }
            
            $rules["receipts.$index.narration"] = 'required|string|max:255';
            
            if ($this->contract->isVatApplicable() && ($receipt['receipt_category'] ?? 'RENT') !== 'VAT') {
                $rules["receipts.$index.vat_rate"] = 'required|numeric|min:0|max:100';
                $rules["receipts.$index.vat_amount"] = 'required|numeric|min:0';
                $rules["receipts.$index.vat_inclusive"] = 'required|boolean';
            } else {
                $rules["receipts.$index.vat_rate"] = 'nullable|numeric|min:0|max:100';
                $rules["receipts.$index.vat_amount"] = 'nullable|numeric|min:0';
                $rules["receipts.$index.vat_inclusive"] = 'nullable|boolean';
            }

            if ($receipt['payment_type'] === 'CHEQUE') {
                $rules["receipts.$index.cheque_no"] = 'required|string|max:50';
                $rules["receipts.$index.cheque_date"] = 'required|date';
                $rules["receipts.$index.cheque_bank"] = 'required|string|max:100';
                $rules["receipts.$index.cheque_image"] = 'required|image|max:10240';
                $rules["receipts.$index.receipt_date"] = 'nullable|date';
            } elseif ($receipt['payment_type'] === 'ONLINE_TRANSFER') {
                $rules["receipts.$index.receipt_date"] = 'required|date';
                $rules["receipts.$index.transaction_reference"] = 'required|string|max:100';
                $rules["receipts.$index.transfer_receipt_image"] = 'nullable|image|max:10240';
            } else {
                $rules["receipts.$index.receipt_date"] = 'required|date';
            }
        }

        return $rules;
    }

    public function submit()
    {
        try {
            // Check if there are any receipts with valid data
            $hasValidReceipts = false;
            foreach ($this->receipts as $receipt) {
                if (!empty($receipt['amount']) && !empty($receipt['narration'])) {
                    $hasValidReceipts = true;
                    break;
                }
            }
            
            if (!$hasValidReceipts) {
                session()->flash('error', 'Please fill in at least one receipt with amount and narration.');
                return;
            }
            
            $validated = $this->validate();

            DB::beginTransaction();

            foreach ($this->receipts as $receiptData) {
                // Prepare data, applying logic for CASH/ONLINE_TRANSFER
                $dataToCreate = [
                    'contract_id' => $this->contract_id,
                    'receipt_category' => $receiptData['receipt_category'],
                    'payment_type' => $receiptData['payment_type'],
                    'amount' => $receiptData['amount'],
                    'receipt_date' => $receiptData['receipt_date'],
                    'narration' => $receiptData['narration'],
                    'cheque_no' => $receiptData['payment_type'] === 'CHEQUE' ? $receiptData['cheque_no'] : null,
                    'cheque_bank' => $receiptData['payment_type'] === 'CHEQUE' ? $receiptData['cheque_bank'] : null,
                    'cheque_date' => $receiptData['payment_type'] === 'CHEQUE' ? $receiptData['cheque_date'] : null,
                    'vat_rate' => $receiptData['vat_rate'] ?? 0,
                    'vat_amount' => $receiptData['vat_amount'] ?? 0,
                    'vat_inclusive' => $receiptData['vat_inclusive'] ?? false,
                    'transaction_reference' => $receiptData['payment_type'] === 'ONLINE_TRANSFER' ? $receiptData['transaction_reference'] : null,
                    'status' => 'PENDING', // Default status
                    'deposit_date' => null, // Default deposit date
                    'remarks' => null, // Default remarks
                ];

                // Apply specific logic for CASH and ONLINE_TRANSFER
                if (in_array($receiptData['payment_type'], ['CASH', 'ONLINE_TRANSFER'])) {
                    $dataToCreate['status'] = 'CLEARED';
                    // Set deposit_date to receipt_date only if receipt_date is not null
                    if (!empty($receiptData['receipt_date'])) {
                        $dataToCreate['deposit_date'] = $receiptData['receipt_date'];
                    }
                    // Set remarks equal to narration
                    $dataToCreate['remarks'] = $receiptData['narration'];
                }

                $receipt = Receipt::create($dataToCreate);

                if ($receiptData['payment_type'] === 'CHEQUE' && isset($receiptData['cheque_image']) && $receiptData['cheque_image']) {
                    try {
                        if (method_exists($receiptData['cheque_image'], 'isValid') && $receiptData['cheque_image']->isValid()) {
                            $receipt->addMedia($receiptData['cheque_image']->getRealPath())
                                ->usingName('Cheque Copy')
                                ->toMediaCollection('cheque_images', 'public');
                        } else {
                            Log::warning('Invalid cheque image file provided');
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to upload cheque image: ' . $e->getMessage());
                    }
                }

                if ($receiptData['payment_type'] === 'ONLINE_TRANSFER' && isset($receiptData['transfer_receipt_image']) && $receiptData['transfer_receipt_image']) {
                    try {
                        if (method_exists($receiptData['transfer_receipt_image'], 'isValid') && $receiptData['transfer_receipt_image']->isValid()) {
                            $receipt->addMedia($receiptData['transfer_receipt_image']->getRealPath())
                                ->usingName('Transfer Receipt')
                                ->toMediaCollection('transfer_receipts', 'public');
                        } else {
                            Log::warning('Invalid transfer receipt image file provided');
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to upload transfer receipt image: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            // Dispatch event to refresh calculations in other components
            $this->dispatch('receiptsUpdated');

            session()->flash('success', 'Receipts recorded successfully.');
            return redirect()->route('contracts.show', $this->contract_id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in receipt form: ' . json_encode($e->errors()));
            Log::error('Current receipts data: ' . json_encode($this->receipts));
            session()->flash('error', 'Validation failed: ' . json_encode($e->errors()));
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating receipt: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            session()->flash('error', 'Error creating receipt: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.receipts.form');
    }
}
