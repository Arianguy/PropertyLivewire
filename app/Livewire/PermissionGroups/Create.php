<?php

namespace App\Livewire\PermissionGroups;

use App\Models\Module;
use App\Models\PermissionGroup;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $module_id = '';
    public $order = 0;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permission_groups,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'module_id' => ['required', 'exists:modules,id'],
            'order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function save()
    {
        $this->validate();

        PermissionGroup::create([
            'name' => $this->name,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'order' => $this->order,
        ]);

        $this->dispatch('notify', [
            'message' => 'Permission group created successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('permission-groups.table');
    }

    public function render()
    {
        return view('livewire.permission-groups.create', [
            'modules' => Module::orderBy('order')->get(),
        ]);
    }
}
