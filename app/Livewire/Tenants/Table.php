<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class Table extends Component
{
    use WithPagination;

    #[Rule('nullable|string|min:2')]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tenants = Tenant::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.tenants.table', [
            'tenants' => $tenants
        ]);
    }

    public function delete(Tenant $tenant)
    {
        $tenant->delete();
        session()->flash('success', 'Tenant deleted successfully.');
    }
}
