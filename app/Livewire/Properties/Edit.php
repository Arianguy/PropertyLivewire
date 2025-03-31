<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use App\Models\Owner;
use Livewire\Component;

class Edit extends Component
{
    public Property $property;

    // Basic information
    public $name = '';
    public $class = '';
    public $type = '';
    public $purchase_date = '';
    public $title_deed_no = '';
    public $mortgage_status = '';
    public $community = '';

    // Location details
    public $plot_no = '';
    public $bldg_no = '';
    public $bldg_name = '';
    public $property_no = '';
    public $floor_detail = '';

    // Area details
    public $suite_area = 0;
    public $balcony_area = 0;
    public $area_sq_mter = 0;
    public $common_area = 0;
    public $area_sq_feet = 0;

    // Owner and value
    public $owner_id = '';
    public $purchase_value = 0;

    // Utilities
    public $dewa_premise_no = '';
    public $dewa_account_no = '';
    public $status = '';
    public $salesdeed = '';
    public $is_visible = true;

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->name = $property->name;
        $this->class = $property->class;
        $this->type = $property->type;
        $this->purchase_date = $property->purchase_date->format('Y-m-d');
        $this->title_deed_no = $property->title_deed_no;
        $this->mortgage_status = $property->mortgage_status;
        $this->community = $property->community;
        $this->plot_no = $property->plot_no;
        $this->bldg_no = $property->bldg_no;
        $this->bldg_name = $property->bldg_name;
        $this->property_no = $property->property_no;
        $this->floor_detail = $property->floor_detail;
        $this->suite_area = $property->suite_area;
        $this->balcony_area = $property->balcony_area;
        $this->area_sq_mter = $property->area_sq_mter;
        $this->common_area = $property->common_area;
        $this->area_sq_feet = $property->area_sq_feet;
        $this->owner_id = $property->owner_id;
        $this->purchase_value = $property->purchase_value;
        $this->dewa_premise_no = $property->dewa_premise_no;
        $this->dewa_account_no = $property->dewa_account_no;
        $this->status = $property->status;
        $this->salesdeed = $property->salesdeed;
        $this->is_visible = $property->is_visible;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:properties,name,' . $this->property->id],
            'class' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'purchase_date' => ['required', 'date'],
            'title_deed_no' => ['required', 'string', 'max:255', 'unique:properties,title_deed_no,' . $this->property->id],
            'mortgage_status' => ['required', 'string', 'max:255'],
            'community' => ['required', 'string', 'max:255'],
            'plot_no' => ['required', 'numeric', 'min:1'],
            'bldg_no' => ['required', 'numeric', 'min:1'],
            'bldg_name' => ['required', 'string', 'max:255'],
            'property_no' => ['required', 'string', 'max:255'],
            'floor_detail' => ['required', 'string', 'max:255'],
            'suite_area' => ['required', 'numeric', 'min:0'],
            'balcony_area' => ['required', 'numeric', 'min:0'],
            'area_sq_mter' => ['required', 'numeric', 'min:0'],
            'common_area' => ['required', 'numeric', 'min:0'],
            'area_sq_feet' => ['required', 'numeric', 'min:0'],
            'owner_id' => ['required', 'exists:owners,id'],
            'purchase_value' => ['required', 'numeric', 'min:0'],
            'dewa_premise_no' => ['nullable', 'numeric', 'unique:properties,dewa_premise_no,' . $this->property->id],
            'dewa_account_no' => ['nullable', 'numeric'],
            'status' => ['required', 'string', 'max:255'],
            'salesdeed' => ['nullable', 'string', 'max:255'],
            'is_visible' => ['boolean'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->property->update([
            'name' => $this->name,
            'class' => $this->class,
            'type' => $this->type,
            'purchase_date' => $this->purchase_date,
            'title_deed_no' => $this->title_deed_no,
            'mortgage_status' => $this->mortgage_status,
            'community' => $this->community,
            'plot_no' => $this->plot_no,
            'bldg_no' => $this->bldg_no,
            'bldg_name' => $this->bldg_name,
            'property_no' => $this->property_no,
            'floor_detail' => $this->floor_detail,
            'suite_area' => $this->suite_area,
            'balcony_area' => $this->balcony_area,
            'area_sq_mter' => $this->area_sq_mter,
            'common_area' => $this->common_area,
            'area_sq_feet' => $this->area_sq_feet,
            'owner_id' => $this->owner_id,
            'purchase_value' => $this->purchase_value,
            'dewa_premise_no' => $this->dewa_premise_no,
            'dewa_account_no' => $this->dewa_account_no,
            'status' => $this->status,
            'salesdeed' => $this->salesdeed,
            'is_visible' => $this->is_visible,
        ]);

        $this->dispatch('notify', [
            'message' => 'Property updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('properties.table');
    }

    public function render()
    {
        return view('livewire.properties.edit', [
            'owners' => Owner::orderBy('name')->get(),
            'propertyClasses' => ['Residential', 'Commercial', 'Industrial', 'Mixed-Use'],
            'propertyTypes' => ['Apartment', 'Villa', 'Townhouse', 'Office', 'Retail', 'Warehouse', 'Land'],
            'mortgageStatuses' => ['Mortgaged', 'Not Mortgaged', 'In Process'],
            'propertyStatuses' => ['Available', 'Rented', 'Under Maintenance', 'Under Renovation', 'Sold'],
        ]);
    }
}
