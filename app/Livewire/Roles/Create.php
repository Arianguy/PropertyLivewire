<?php

namespace App\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $permissions = [];

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $role->syncPermissions($this->permissions);

        $this->dispatch('notify', [
            'message' => 'Role created successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('roles.table');
    }

    public function render()
    {
        $permissions = Permission::with('module', 'permissionGroup')
            ->orderBy('name')
            ->get();

        // Group permissions by module name, handle null modules
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return $permission->module ? $permission->module->name : 'Other';
        });

        return view('livewire.roles.create', [
            'availablePermissions' => $groupedPermissions,
        ]);
    }
}
