<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropertySale extends Component
{
    public Property $property;
    
    #[Validate('required|date|before_or_equal:today')]
    public $sale_date;
    
    #[Validate('required|numeric|min:1')]
    public $sale_price;
    
    #[Validate('required|string|max:255')]
    public $buyer_name;
    
    #[Validate('nullable|string|max:1000')]
    public $sale_notes;
    
    public $showConfirmation = false;
    public $saleConfirmed = false;
    
    public function mount(Property $property)
    {
        $this->property = $property;
        $this->sale_date = now()->format('Y-m-d');
        
        // Check if property status is VACANT
        if ($this->property->status !== 'VACANT') {
            session()->flash('error', 'Property can only be sold when status is VACANT. Current status: ' . $this->property->status);
            return redirect()->route('properties.show', $this->property);
        }
    }
    
    public function showSaleConfirmation()
    {
        // Refresh property to get latest status
        $this->property->refresh();
        
        // Check if property status is still VACANT
        if ($this->property->status !== 'VACANT') {
            session()->flash('error', 'Property can only be sold when status is VACANT. Current status: ' . $this->property->status);
            return redirect()->route('properties.show', $this->property);
        }
        
        $this->validate();
        $this->showConfirmation = true;
    }
    
    public function cancelSale()
    {
        $this->showConfirmation = false;
    }
    
    public function confirmSale()
    {
        // Refresh property to get latest status
        $this->property->refresh();
        
        // Check if property status is still VACANT
        if ($this->property->status !== 'VACANT') {
            session()->flash('error', 'Property can only be sold when status is VACANT. Current status: ' . $this->property->status);
            return redirect()->route('properties.show', $this->property);
        }
        
        $this->validate();
        
        try {
            DB::transaction(function () {
                // Update property with sale information
                $this->property->update([
                    'status' => 'SOLD',
                    'sale_date' => $this->sale_date,
                    'sale_price' => $this->sale_price,
                    'buyer_name' => $this->buyer_name,
                    'sale_notes' => $this->sale_notes,
                    'is_archived' => true,
                    'archived_at' => now(),
                    'archived_by' => Auth::id(),
                ]);
            });
            
            $this->saleConfirmed = true;
            $this->showConfirmation = false;
            
            session()->flash('success', 'Property has been successfully sold and archived.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while processing the sale: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.property-sale');
    }
}