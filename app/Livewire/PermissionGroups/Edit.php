<?php

namespace App\Livewire\PermissionGroups;

use App\Models\Module;
use App\Models\PermissionGroup;
use Livewire\Component;

class Edit extends Component
{
    public PermissionGroup $permissionGroup;
    public $name = '';
    public $description = '';
    public $module_id = '';
    public $order = 0;

    public function mount(PermissionGroup $permissionGroup)
    {
        $this->permissionGroup = $permissionGroup;
        $this->name = $permissionGroup->name;
        $this->description = $permissionGroup->description;
        $this->module_id = $permissionGroup->module_id;
        $this->order = $permissionGroup->order;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permission_groups,name,' . $this->permissionGroup->id],
            'description' => ['nullable', 'string', 'max:255'],
            'module_id' => ['required', 'exists:modules,id'],
            'order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->permissionGroup->update([
            'name' => $this->name,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'order' => $this->order,
        ]);

        $this->dispatch('notify', [
            'message' => 'Permission group updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('permission-groups.table');
    }

    public function render()
    {
        return view('livewire.permission-groups.edit', [
            'modules' => Module::orderBy('order')->get(),
        ]);
    }
}
