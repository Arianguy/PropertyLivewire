<?php

namespace App\Livewire\Receipts;

use App\Models\Contract;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Contract::with(['tenant', 'property'])
            ->where('validity', 'YES');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tenant', function ($tenantQuery) {
                        $tenantQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('property', function ($propertyQuery) {
                        $propertyQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $contracts = $query->latest()->paginate(10);

        return view('livewire.receipts.index', [
            'contracts' => $contracts
        ]);
    }
}
