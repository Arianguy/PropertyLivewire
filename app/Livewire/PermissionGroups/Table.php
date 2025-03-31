<?php

namespace App\Livewire\PermissionGroups;

use App\Models\PermissionGroup;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public function delete(PermissionGroup $permissionGroup)
    {
        $permissionGroup->delete();

        $this->dispatch('notify', [
            'message' => 'Permission group deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.permission-groups.table', [
            'permissionGroups' => PermissionGroup::with('module')
                ->orderBy('order')
                ->paginate(10),
        ]);
    }
}
