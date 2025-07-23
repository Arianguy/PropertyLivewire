<?php

namespace App\Livewire\Dashboard;

use App\Models\Property;
use App\Models\Contract;
use Livewire\Component;

class PropertyOverview extends Component
{
    public $metrics = [];

    public function mount()
    {
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        $totalProperties = Property::count();
        $occupiedProperties = Property::whereHas('contracts', function ($query) {
            $query->where('validity', 'YES');
        })->count();
        $vacantProperties = $totalProperties - $occupiedProperties;
        $occupancyRate = $totalProperties > 0 ? round(($occupiedProperties / $totalProperties) * 100, 1) : 0;

        // Get properties by status
        $propertiesByStatus = Property::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get recent property additions
        $recentProperties = Property::latest()->take(3)->get();

        // Get expiring contracts (next 30 days)
        $expiringContracts = Contract::where('validity', 'YES')
            ->whereBetween('cend', [now(), now()->addDays(30)])
            ->count();

        $this->metrics = [
            'total_properties' => $totalProperties,
            'occupied_properties' => $occupiedProperties,
            'vacant_properties' => $vacantProperties,
            'occupancy_rate' => $occupancyRate,
            'properties_by_status' => $propertiesByStatus,
            'recent_properties' => $recentProperties,
            'expiring_contracts' => $expiringContracts,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.property-overview');
    }
}