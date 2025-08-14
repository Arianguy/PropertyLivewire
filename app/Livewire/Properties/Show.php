<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use Livewire\Component;

class Show extends Component
{
    public Property $property;
    
    public function mount(Property $property)
    {
        $this->property = $property->load(['owner', 'contracts', 'archivedBy']);
    }
    
    public function render()
    {
        return view('livewire.properties.show');
    }
}