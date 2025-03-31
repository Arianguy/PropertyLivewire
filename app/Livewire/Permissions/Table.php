<?php

namespace App\Livewire\Permissions;

use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public function deletePermission(Permission $permission)
    {
        $permission->delete();
        $this->dispatch('notify', [
            'message' => 'Permission deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.permissions.table', [
            'permissions' => Permission::with(['module', 'permissionGroup'])
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
