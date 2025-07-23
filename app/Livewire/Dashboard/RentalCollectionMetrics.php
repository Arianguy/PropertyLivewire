<?php

namespace App\Livewire\Dashboard;

use App\Models\Contract;
use App\Models\Receipt;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RentalCollectionMetrics extends Component
{
    public $currentMonth;
    public $previousMonth;
    public $metrics = [];
    public $chartData = [];
    public $agingData = [];
    public $tenantStatusData = [];

    public function mount()
    {
        $this->currentMonth = Carbon::now();
        $this->previousMonth = Carbon::now()->subMonth();
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        $this->metrics = [
            'monthly_collections' => $this->getMonthlyCollections(),
            'outstanding_payments' => $this->getOutstandingPayments(),
            'collection_rate' => $this->getCollectionRate(),
            'overdue_amounts' => $this->getOverdueAmounts(),
            'late_fees' => $this->getLateFeeCollections(),
            'growth_metrics' => $this->getGrowthMetrics(),
        ];

        $this->chartData = $this->getRecentPaymentTrends();
        $this->agingData = $this->getAgingAnalysis();
        $this->tenantStatusData = $this->getTenantPaymentStatus();
    }

    private function getMonthlyCollections()
    {
        $currentMonthTotal = Receipt::where('status', 'CLEARED')
            ->where('receipt_category', 'RENT')
            ->whereMonth('receipt_date', $this->currentMonth->month)
            ->whereYear('receipt_date', $this->currentMonth->year)
            ->sum('amount');

        $previousMonthTotal = Receipt::where('status', 'CLEARED')
            ->where('receipt_category', 'RENT')
            ->whereMonth('receipt_date', $this->previousMonth->month)
            ->whereYear('receipt_date', $this->previousMonth->year)
            ->sum('amount');

        $growth = $previousMonthTotal > 0 
            ? (($currentMonthTotal - $previousMonthTotal) / $previousMonthTotal) * 100 
            : 0;

        return [
            'current' => $currentMonthTotal,
            'previous' => $previousMonthTotal,
            'growth' => round($growth, 2),
            'formatted_current' => number_format($currentMonthTotal, 2),
            'formatted_previous' => number_format($previousMonthTotal, 2),
        ];
    }

    private function getOutstandingPayments()
    {
        // Calculate expected rent for current month
        $expectedRent = Contract::where('validity', 'YES')
            ->where('cstart', '<=', $this->currentMonth->endOfMonth())
            ->where('cend', '>=', $this->currentMonth->startOfMonth())
            ->sum('amount');

        // Calculate actual collections for current month
        $actualCollections = Receipt::where('status', 'CLEARED')
            ->where('receipt_category', 'RENT')
            ->whereMonth('receipt_date', $this->currentMonth->month)
            ->whereYear('receipt_date', $this->currentMonth->year)
            ->sum('amount');

        $outstanding = max(0, $expectedRent - $actualCollections);

        return [
            'amount' => $outstanding,
            'formatted' => number_format($outstanding, 2),
            'expected' => $expectedRent,
            'collected' => $actualCollections,
        ];
    }

    private function getCollectionRate()
    {
        $expected = $this->metrics['outstanding_payments']['expected'] ?? 0;
        $collected = $this->metrics['outstanding_payments']['collected'] ?? 0;
        
        if ($expected == 0) {
            return [
                'rate' => 100,
                'status' => 'excellent',
            ];
        }
        
        $rate = ($collected / $expected) * 100;
        
        return [
            'rate' => round($rate, 1),
            'status' => $rate >= 95 ? 'excellent' : ($rate >= 85 ? 'good' : ($rate >= 70 ? 'fair' : 'poor')),
        ];
    }

    private function getOverdueAmounts()
    {
        $today = Carbon::now();
        
        // Get overdue receipts (cheques that are past due date and still pending/bounced)
        $overdueReceipts = Receipt::where('payment_type', 'CHEQUE')
            ->whereIn('status', ['PENDING', 'BOUNCED'])
            ->where('cheque_date', '<', $today)
            ->get();

        $aging = [
            '1-30' => 0,
            '31-60' => 0,
            '61-90' => 0,
            '90+' => 0,
        ];

        $totalOverdue = 0;

        foreach ($overdueReceipts as $receipt) {
            $daysPastDue = $today->diffInDays(Carbon::parse($receipt->cheque_date));
            $amount = $receipt->amount;
            $totalOverdue += $amount;

            if ($daysPastDue <= 30) {
                $aging['1-30'] += $amount;
            } elseif ($daysPastDue <= 60) {
                $aging['31-60'] += $amount;
            } elseif ($daysPastDue <= 90) {
                $aging['61-90'] += $amount;
            } else {
                $aging['90+'] += $amount;
            }
        }

        return [
            'total' => $totalOverdue,
            'formatted_total' => number_format($totalOverdue, 2),
            'aging' => $aging,
            'count' => $overdueReceipts->count(),
        ];
    }

    private function getLateFeeCollections()
    {
        // Assuming late fees are tracked as a separate payment type or in payment descriptions
        $lateFees = Payment::where('description', 'LIKE', '%late fee%')
            ->orWhere('description', 'LIKE', '%penalty%')
            ->whereMonth('paid_at', $this->currentMonth->month)
            ->whereYear('paid_at', $this->currentMonth->year)
            ->sum('amount');

        return [
            'amount' => $lateFees,
            'formatted' => number_format($lateFees, 2),
        ];
    }

    private function getGrowthMetrics()
    {
        $currentYear = $this->currentMonth->year;
        $previousYear = $currentYear - 1;

        $currentYearTotal = Receipt::where('status', 'CLEARED')
            ->where('receipt_category', 'RENT')
            ->whereYear('receipt_date', $currentYear)
            ->sum('amount');

        $previousYearTotal = Receipt::where('status', 'CLEARED')
            ->where('receipt_category', 'RENT')
            ->whereYear('receipt_date', $previousYear)
            ->sum('amount');

        $yearOverYearGrowth = $previousYearTotal > 0 
            ? (($currentYearTotal - $previousYearTotal) / $previousYearTotal) * 100 
            : 0;

        return [
            'yoy_growth' => round($yearOverYearGrowth, 2),
            'current_year_total' => $currentYearTotal,
            'previous_year_total' => $previousYearTotal,
        ];
    }

    private function getRecentPaymentTrends()
    {
        $last6Months = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyTotal = Receipt::where('status', 'CLEARED')
                ->where('receipt_category', 'RENT')
                ->whereMonth('receipt_date', $month->month)
                ->whereYear('receipt_date', $month->year)
                ->sum('amount');
            
            $last6Months[] = $monthlyTotal;
            $labels[] = $month->format('M Y');
        }

        return [
            'labels' => $labels,
            'data' => $last6Months,
        ];
    }

    private function getAgingAnalysis()
    {
        $overdue = $this->getOverdueAmounts();
        $aging = $overdue['aging'];
        
        return [
            'labels' => ['1-30 Days', '31-60 Days', '61-90 Days', '90+ Days'],
            'data' => [
                $aging['1-30'],
                $aging['31-60'],
                $aging['61-90'],
                $aging['90+'],
            ],
            'colors' => ['#fbbf24', '#f59e0b', '#d97706', '#dc2626'],
        ];
    }

    private function getTenantPaymentStatus()
    {
        $activeContracts = Contract::where('validity', 'YES')->count();
        
        // Get tenants who paid this month
        $paidTenants = Receipt::where('status', 'CLEARED')
            ->where('receipt_category', 'RENT')
            ->whereMonth('receipt_date', $this->currentMonth->month)
            ->whereYear('receipt_date', $this->currentMonth->year)
            ->distinct('contract_id')
            ->count();

        // Get tenants with overdue payments
        $overdueTenants = Receipt::where('payment_type', 'CHEQUE')
            ->whereIn('status', ['PENDING', 'BOUNCED'])
            ->where('cheque_date', '<', Carbon::now())
            ->distinct('contract_id')
            ->count();

        $pendingTenants = max(0, $activeContracts - $paidTenants - $overdueTenants);

        return [
            'paid' => $paidTenants,
            'overdue' => $overdueTenants,
            'pending' => $pendingTenants,
            'total' => $activeContracts,
            'paid_percentage' => $activeContracts > 0 ? round(($paidTenants / $activeContracts) * 100, 1) : 0,
        ];
    }

    public function refreshMetrics()
    {
        $this->loadMetrics();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Metrics refreshed successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.rental-collection-metrics');
    }
}