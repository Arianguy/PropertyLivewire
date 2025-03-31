<?php

namespace App\Livewire\Owners;

use App\Models\Owner;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteOwner(Owner $owner)
    {
        // Check if owner has properties
        if ($owner->properties()->count() > 0) {
            $this->addError('delete', 'Cannot delete an owner with properties. Please delete or reassign the properties first.');
            return;
        }

        $owner->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Owner deleted successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.owners.table', [
            'owners' => Owner::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('eid', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('mobile', 'like', '%' . $this->search . '%')
                ->orWhere('nakheelno', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ]);
    }
}
