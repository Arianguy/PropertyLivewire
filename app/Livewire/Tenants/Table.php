<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;

class Table extends Component
{
    use WithPagination;

    #[Rule('nullable|string|min:2')]
    public $search = '';

    public function mount()
    {
        // Check authorization at component level
        if (!Auth::user()->hasRole('Super Admin') && !Auth::user()->can('view tenants')) {
            abort(403, 'Unauthorized action. You do not have permission to view tenants.');
        }
    }

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
            ->paginate(20);

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
