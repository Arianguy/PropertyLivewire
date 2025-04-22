<?php

namespace App\Http\Controllers;

use App\Livewire\Payments\PaymentForm;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        Gate::authorize('view payments');
        return view('livewire.payments.payments-wrapper');
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        Gate::authorize('create payments');
        return view('livewire.payments.payment-wrapper');
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        Gate::authorize('edit payments');
        return view('livewire.payments.payment-wrapper', [
            'payment' => $payment
        ]);
    }
}
