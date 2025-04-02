<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;
use Livewire\WithPagination;

class RenewalList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.contracts.renewal-list', [
            'validContracts' => Contract::where('validity', 'YES')
                ->whereDoesntHave('renewals')
                ->with(['tenant', 'property'])
                ->when($this->search, function ($query) {
                    return $query->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhereHas('tenant', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('property', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
