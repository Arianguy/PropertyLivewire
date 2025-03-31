<?php

namespace App\Livewire\Modules;

use App\Models\Module;
use Livewire\Component;

class Edit extends Component
{
    public Module $module;
    public $name = '';
    public $description = '';
    public $icon = '';
    public $order = 0;

    public function mount(Module $module)
    {
        $this->module = $module;
        $this->name = $module->name;
        $this->description = $module->description;
        $this->icon = $module->icon;
        $this->order = $module->order;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:modules,name,' . $this->module->id],
            'description' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->module->update([
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'order' => $this->order,
        ]);

        $this->dispatch('notify', [
            'message' => 'Module updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('modules.table');
    }

    public function render()
    {
        return view('livewire.modules.edit');
    }
}
