<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteProperty(Property $property)
    {
        $property->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Property deleted successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.properties.table', [
            'properties' => Property::with('owner')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('property_no', 'like', '%' . $this->search . '%')
                        ->orWhere('title_deed_no', 'like', '%' . $this->search . '%')
                        ->orWhere('community', 'like', '%' . $this->search . '%')
                        ->orWhere('bldg_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('owner', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->where('is_visible', true)
                ->paginate(10),
        ]);
    }
}
