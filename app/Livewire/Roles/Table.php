<?php

namespace App\Livewire\Roles;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public function deleteRole(Role $role)
    {
        if ($role->name === 'Super Admin') {
            $this->addError('error', 'Cannot delete Super Admin role.');
            return;
        }

        $role->delete();
        $this->dispatch('notify', [
            'message' => 'Role deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.roles.table', [
            'roles' => Role::with('permissions')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
