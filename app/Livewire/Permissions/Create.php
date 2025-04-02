<?php

namespace App\Livewire\Permissions;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $module_id = '';
    public $permission_group_id = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'module_id' => ['required', 'exists:modules,id'],
            'permission_group_id' => ['required', 'exists:permission_groups,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $permission = Permission::create([
            'name' => $this->name,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'permission_group_id' => $this->permission_group_id,
        ]);

        // Automatically assign the new permission to Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }

        $this->dispatch('notify', [
            'message' => 'Permission created successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('permissions.table');
    }

    public function render()
    {
        return view('livewire.permissions.create', [
            'modules' => Module::orderBy('name')->get(),
            'permissionGroups' => PermissionGroup::orderBy('name')->get(),
        ]);
    }
}
