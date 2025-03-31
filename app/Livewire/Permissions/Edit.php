<?php

namespace App\Livewire\Permissions;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Livewire\Component;

class Edit extends Component
{
    public Permission $permission;
    public $name = '';
    public $description = '';
    public $module_id = '';
    public $permission_group_id = '';

    public function mount(Permission $permission)
    {
        $this->permission = $permission;
        $this->name = $permission->name;
        $this->description = $permission->description;
        $this->module_id = $permission->module_id;
        $this->permission_group_id = $permission->permission_group_id;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $this->permission->id],
            'description' => ['nullable', 'string', 'max:255'],
            'module_id' => ['required', 'exists:modules,id'],
            'permission_group_id' => ['required', 'exists:permission_groups,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->permission->update([
            'name' => $this->name,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'permission_group_id' => $this->permission_group_id,
        ]);

        $this->dispatch('notify', [
            'message' => 'Permission updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('permissions.table');
    }

    public function render()
    {
        return view('livewire.permissions.edit', [
            'modules' => Module::orderBy('name')->get(),
            'permissionGroups' => PermissionGroup::orderBy('name')->get(),
        ]);
    }
}
