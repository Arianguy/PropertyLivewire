<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptsController extends Controller
{
    /**
     * Display a listing of the contracts for receipt creation.
     */
    public function index()
    {
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
        $receipts = Receipt::where('contract_id', $contract->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('receipts.list', compact('contract', 'receipts'));
    }
}
