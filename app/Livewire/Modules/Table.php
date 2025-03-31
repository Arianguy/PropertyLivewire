<?php

namespace App\Livewire\Modules;

use App\Models\Module;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public function deleteModule(Module $module)
    {
        if ($module->permissions()->exists()) {
            $this->addError('error', 'Cannot delete module with associated permissions.');
            return;
        }

        $module->delete();
        $this->dispatch('notify', [
            'message' => 'Module deleted successfully.',
            'type' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.modules.table', [
            'modules' => Module::withCount('permissions')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
