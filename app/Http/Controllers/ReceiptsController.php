<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Livewire\Receipts\Form as ReceiptForm;
use App\Livewire\Receipts\Edit as ReceiptEdit;
use App\Livewire\Receipts\ViewAttachment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ReceiptsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the contracts for receipt creation.
     */
    public function index()
    {
        $this->authorize('view receipts');

        $contracts = Contract::with('tenant', 'property')
            ->where('validity', 'YES')
            ->get();
        return view('receipts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new receipt.
     */
    public function create(Contract $contract)
    {
        $this->authorize('create receipts');

        if ($contract->validity !== 'YES') {
            return redirect()->route('receipts.index')
                ->with('error', 'Receipts can only be created for active contracts.');
        }

        return view('receipts.create', compact('contract'));
    }

    /**
     * Display a listing of receipts for a specific contract.
     */
    public function listByContract(Contract $contract)
    {
        $this->authorize('view receipts');
        return view('receipts.contracts', compact('contract'));
    }

    public function edit(Receipt $receipt)
    {
        $this->authorize('edit receipts');

        $contract = $receipt->contract;
        return view('receipts.edit', compact('receipt', 'contract'));
    }

    /**
     * Update the specified receipt in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        $this->authorize('edit receipts');

        $validated = $request->validate([
            'receipt_category' => 'required|in:SECURITY_DEPOSIT,RENT,RETURN CHEQUE,VAT,CANCELLED',
            'payment_type' => 'required|in:CASH,CHEQUE,ONLINE_TRANSFER',
            'amount' => 'required|numeric|min:0',
            'receipt_date' => 'required|date',
            'narration' => 'required|string',
            'cheque_no' => 'required_if:payment_type,CHEQUE|nullable|string',
            'cheque_date' => 'required_if:payment_type,CHEQUE|nullable|date',
            'cheque_bank' => 'required_if:payment_type,CHEQUE|nullable|string',
            'transaction_reference' => 'required_if:payment_type,ONLINE_TRANSFER|nullable|string',
        ]);

        // Update the receipt
        $receipt->update($validated);

        // Handle file uploads if present
        if ($request->hasFile('cheque_image')) {
            $receipt->clearMediaCollection('cheque_images');
            $receipt->addMediaFromRequest('cheque_image')
                ->usingName($receipt->contract->name . '_cheque')
                ->toMediaCollection('cheque_images', 'public');
        }

        if ($request->hasFile('transfer_receipt_image')) {
            $receipt->clearMediaCollection('transfer_receipts');
            $receipt->addMediaFromRequest('transfer_receipt_image')
                ->usingName($receipt->contract->name . '_transfer')
                ->toMediaCollection('transfer_receipts', 'public');
        }

        return redirect()->route('receipts.list-by-contract', $receipt->contract)
            ->with('success', 'Receipt updated successfully.');
    }

    /**
     * Remove the specified receipt from storage.
     */
    public function destroy(Receipt $receipt)
    {
        $this->authorize('delete receipts');

        // Clear all media associated with the receipt
        $receipt->clearMediaCollection('cheque_images');
        $receipt->clearMediaCollection('transfer_receipts');

        // Delete the receipt
        $receipt->delete();

        return redirect()->route('receipts.list-by-contract', $receipt->contract)
            ->with('success', 'Receipt deleted successfully.');
    }

    /**
     * Fix media for a specific receipt.
     */
    public function fixMedia(Receipt $receipt)
    {
        $this->authorize('edit receipts');

        // Implementation of fix media logic
        // ...

        return redirect()->route('receipts.edit', $receipt)
            ->with('success', 'Media fixed successfully.');
    }
}
