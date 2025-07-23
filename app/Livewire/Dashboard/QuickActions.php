<?php

namespace App\Livewire\Dashboard;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Receipt;
use Carbon\Carbon;
use Livewire\Component;

class QuickActions extends Component
{
    public $stats = [];
    public $alerts = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadAlerts();
    }

    public function loadStats()
    {
        $this->stats = [
            'pending_receipts' => Receipt::where('status', 'PENDING')->count(),
            'expiring_contracts' => Contract::where('validity', 'YES')
                ->whereBetween('cend', [now(), now()->addDays(30)])
                ->count(),
            'vacant_properties' => Property::where('status', 'VACANT')->count(),
            'bounced_cheques' => Receipt::where('status', 'BOUNCED')->count(),
        ];
    }

    public function loadAlerts()
    {
        $alerts = collect();

        // Expiring contracts alert
        if ($this->stats['expiring_contracts'] > 0) {
            $alerts->push([
                'type' => 'warning',
                'icon' => 'exclamation',
                'title' => 'Contracts Expiring Soon',
                'message' => $this->stats['expiring_contracts'] . ' contract(s) expire within 30 days',
                'action' => 'View Contracts',
                'route' => 'contracts.table',
                'color' => 'yellow'
            ]);
        }

        // Bounced cheques alert
        if ($this->stats['bounced_cheques'] > 0) {
            $alerts->push([
                'type' => 'error',
                'icon' => 'x-circle',
                'title' => 'Bounced Cheques',
                'message' => $this->stats['bounced_cheques'] . ' cheque(s) need attention',
                'action' => 'Manage Cheques',
                'route' => 'cheque-management',
                'color' => 'red'
            ]);
        }

        // Vacant properties alert
        if ($this->stats['vacant_properties'] > 0) {
            $alerts->push([
                'type' => 'info',
                'icon' => 'information-circle',
                'title' => 'Vacant Properties',
                'message' => $this->stats['vacant_properties'] . ' property(ies) available for rent',
                'action' => 'View Properties',
                'route' => 'properties.table',
                'color' => 'blue'
            ]);
        }

        // Pending receipts alert
        if ($this->stats['pending_receipts'] > 0) {
            $alerts->push([
                'type' => 'warning',
                'icon' => 'clock',
                'title' => 'Pending Receipts',
                'message' => $this->stats['pending_receipts'] . ' receipt(s) awaiting clearance',
                'action' => 'View Receipts',
                'route' => 'receipts.index',
                'color' => 'orange'
            ]);
        }

        $this->alerts = $alerts->take(3)->all();
    }

    public function getQuickActions()
    {
        return [
            [
                'title' => 'Add New Tenant',
                'description' => 'Register a new tenant',
                'icon' => 'user-add',
                'route' => 'tenants.create',
                'color' => 'blue'
            ],
            [
                'title' => 'Create Contract',
                'description' => 'New rental agreement',
                'icon' => 'document-add',
                'route' => 'contracts.create',
                'color' => 'green'
            ],
            [
                'title' => 'Record Payment',
                'description' => 'Add payment receipt',
                'icon' => 'cash',
                'route' => 'payments.create',
                'color' => 'purple'
            ],
            [
                'title' => 'Add Property',
                'description' => 'Register new property',
                'icon' => 'home',
                'route' => 'properties.create',
                'color' => 'indigo'
            ],
            [
                'title' => 'Generate Report',
                'description' => 'Financial reports',
                'icon' => 'chart-bar',
                'route' => 'reports.security-deposits',
                'color' => 'pink'
            ],
            [
                'title' => 'Manage Cheques',
                'description' => 'Cheque status tracking',
                'icon' => 'credit-card',
                'route' => 'cheque-management',
                'color' => 'yellow'
            ]
        ];
    }

    public function getIcon($iconType)
    {
        return match($iconType) {
            'user-add' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
            'document-add' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'cash' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
            'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'chart-bar' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            'credit-card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
            'exclamation' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
            'x-circle' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'information-circle' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }

    public function render()
    {
        return view('livewire.dashboard.quick-actions', [
            'quickActions' => $this->getQuickActions()
        ]);
    }
}