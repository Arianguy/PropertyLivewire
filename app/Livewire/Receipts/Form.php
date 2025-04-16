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
        ];
    }

    public function removeReceipt($index)
    {
        unset($this->receipts[$index]);
        $this->receipts = array_values($this->receipts);
    }

    protected function rules()
    {
        $rules = [];

        foreach ($this->receipts as $index => $receipt) {
            $rules["receipts.$index.receipt_category"] = 'required|in:SECURITY_DEPOSIT,RENT';
            $rules["receipts.$index.payment_type"] = 'required|in:CASH,CHEQUE,ONLINE_TRANSFER';
            $rules["receipts.$index.amount"] = 'required|numeric|min:0.01';
            $rules["receipts.$index.narration"] = 'required|string|max:255';

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
            $validated = $this->validate();

            DB::beginTransaction();

            foreach ($this->receipts as $receiptData) {
                $receipt = Receipt::create([
                    'contract_id' => $this->contract_id,
                    'receipt_category' => $receiptData['receipt_category'],
                    'payment_type' => $receiptData['payment_type'],
                    'amount' => $receiptData['amount'],
                    'receipt_date' => $receiptData['receipt_date'],
                    'narration' => $receiptData['narration'],
                    'cheque_no' => $receiptData['payment_type'] === 'CHEQUE' ? $receiptData['cheque_no'] : null,
                    'cheque_bank' => $receiptData['payment_type'] === 'CHEQUE' ? $receiptData['cheque_bank'] : null,
                    'cheque_date' => $receiptData['payment_type'] === 'CHEQUE' ? $receiptData['cheque_date'] : null,
                    'transaction_reference' => $receiptData['payment_type'] === 'ONLINE_TRANSFER' ? $receiptData['transaction_reference'] : null,
                    'status' => $receiptData['payment_type'] === 'CASH' ? 'CLEARED' : 'PENDING',
                ]);

                if ($receiptData['payment_type'] === 'CHEQUE' && isset($receiptData['cheque_image']) && $receiptData['cheque_image']) {
                    try {
                        // Ensure file is properly uploaded
                        if (method_exists($receiptData['cheque_image'], 'isValid') && $receiptData['cheque_image']->isValid()) {
                            $receipt->addMedia($receiptData['cheque_image']->getRealPath())
                                ->usingName('Cheque Copy')
                                ->toMediaCollection('cheque_images', 'public');
                        } else {
                            Log::warning('Invalid cheque image file provided');
                        }
                    } catch (\Exception $e) {
                        // Log the error but continue with the receipt creation
                        Log::error('Failed to upload cheque image: ' . $e->getMessage());
                    }
                }

                if ($receiptData['payment_type'] === 'ONLINE_TRANSFER' && isset($receiptData['transfer_receipt_image']) && $receiptData['transfer_receipt_image']) {
                    try {
                        // Ensure file is properly uploaded
                        if (method_exists($receiptData['transfer_receipt_image'], 'isValid') && $receiptData['transfer_receipt_image']->isValid()) {
                            $receipt->addMedia($receiptData['transfer_receipt_image']->getRealPath())
                                ->usingName('Transfer Receipt')
                                ->toMediaCollection('transfer_receipts', 'public');
                        } else {
                            Log::warning('Invalid transfer receipt image file provided');
                        }
                    } catch (\Exception $e) {
                        // Log the error but continue with the receipt creation
                        Log::error('Failed to upload transfer receipt image: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            session()->flash('success', 'Receipts recorded successfully.');
            return redirect()->route('contracts.show', $this->contract_id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in receipt form: ' . json_encode($e->errors()));
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
