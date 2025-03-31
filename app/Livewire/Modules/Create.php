<?php

namespace App\Livewire\Modules;

use App\Models\Module;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $description = '';
    public $icon = '';
    public $order = 0;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:modules,name'],
            'description' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function save()
    {
        $this->validate();

        Module::create([
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'order' => $this->order,
        ]);

        $this->dispatch('notify', [
            'message' => 'Module created successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('modules.table');
    }

    public function render()
    {
        return view('livewire.modules.create');
    }
}
