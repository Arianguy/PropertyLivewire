<?php

namespace App\Livewire\Dashboard;

use App\Models\Receipt;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Property;
use Carbon\Carbon;
use Livewire\Component;

class RecentActivity extends Component
{
    public $activities = [];

    public function mount()
    {
        $this->loadRecentActivity();
    }

    public function loadRecentActivity()
    {
        $activities = collect();

        // Recent receipts (last 7 days)
        $recentReceipts = Receipt::with(['contract.tenant', 'contract.property'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentReceipts as $receipt) {
            $activities->push([
                'type' => 'receipt',
                'icon' => 'receipt',
                'title' => 'Payment Received',
                'description' => "AED " . number_format($receipt->amount, 2) . " from " . ($receipt->contract->tenant->name ?? 'Unknown'),
                'property' => $receipt->contract->property->name ?? 'Unknown Property',
                'time' => $receipt->created_at,
                'status' => $receipt->status,
                'color' => $this->getStatusColor($receipt->status),
            ]);
        }

        // Recent contracts (last 7 days)
        $recentContracts = Contract::with(['tenant', 'property'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->take(3)
            ->get();

        foreach ($recentContracts as $contract) {
            $activities->push([
                'type' => 'contract',
                'icon' => 'document',
                'title' => 'New Contract',
                'description' => "Contract signed with " . ($contract->tenant->name ?? 'Unknown'),
                'property' => $contract->property->name ?? 'Unknown Property',
                'time' => $contract->created_at,
                'status' => $contract->validity,
                'color' => $contract->validity === 'YES' ? 'green' : 'gray',
            ]);
        }

        // Recent payments (last 7 days)
        $recentPayments = Payment::with(['property', 'contract.tenant'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->take(3)
            ->get();

        foreach ($recentPayments as $payment) {
            $activities->push([
                'type' => 'payment',
                'icon' => 'cash',
                'title' => 'Payment Recorded',
                'description' => "AED " . number_format($payment->amount, 2) . " - " . $payment->payment_method,
                'property' => $payment->property->name ?? 'Unknown Property',
                'time' => $payment->created_at,
                'status' => 'completed',
                'color' => 'blue',
            ]);
        }

        // Recent properties (last 7 days)
        $recentProperties = Property::with('owner')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->take(2)
            ->get();

        foreach ($recentProperties as $property) {
            $activities->push([
                'type' => 'property',
                'icon' => 'building',
                'title' => 'Property Added',
                'description' => "New property registered by " . ($property->owner->name ?? 'Unknown'),
                'property' => $property->name,
                'time' => $property->created_at,
                'status' => $property->status,
                'color' => 'purple',
            ]);
        }

        // Sort by time (most recent first) and take top 10
        $this->activities = $activities->sortByDesc('time')->take(10)->values()->all();
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'CLEARED' => 'green',
            'PENDING' => 'yellow',
            'BOUNCED' => 'red',
            default => 'gray'
        };
    }

    public function getIcon($iconType)
    {
        return match($iconType) {
            'receipt' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'document' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'cash' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
            'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }

    public function render()
    {
        return view('livewire.dashboard.recent-activity');
    }
}