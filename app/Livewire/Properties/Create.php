<?php

namespace App\Livewire\Properties;

use App\Models\Owner;
use Livewire\Component;
use App\Models\Property;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    use WithFileUploads;

    // Basic information
    public $name = '';
    public $class = '';
    public $type = '';

    // Property Details
    public $purchase_date;
    public $title_deed_no = '';
    public $mortgage_status = 'None';

    // Location details
    public $community = '';
    public $plot_no = null;
    public $bldg_no = null;
    public $bldg_name = '';
    public $property_no = '';
    public $floor_detail = '';

    // Area details
    public $suite_area = null;
    public $balcony_area = null;
    public $area_sq_mter = null;
    public $common_area = null;
    public $area_sq_feet = null;

    // Owner and value
    public $owner_id = '';
    public $purchase_value = null;

    // Utilities
    public $status = 'VACANT';
    public $dewa_premise_no = null;
    public $dewa_account_no = null;
    public $is_visible = true;

    // Document Uploads
    public $deedFiles = [];
    public $uploadedDeeds = false;

    public function mount()
    {
        $this->purchase_date = now()->format('Y-m-d');
    }

    public function updatedDeedFiles()
    {
        $this->uploadedDeeds = true;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:properties,name',
            'class' => 'required|string',
            'type' => 'required|string',
            'purchase_date' => 'required|date',
            'title_deed_no' => 'required|string|max:255|unique:properties,title_deed_no',
            'mortgage_status' => 'required|string',
            'community' => 'required|string|max:255',
            'plot_no' => 'required|numeric|min:1',
            'bldg_no' => 'required|numeric|min:1',
            'bldg_name' => 'required|string|max:255',
            'property_no' => 'required|string|max:255',
            'floor_detail' => 'required|string|max:255',
            'suite_area' => 'required|numeric|min:0',
            'balcony_area' => 'required|numeric|min:0',
            'area_sq_mter' => 'required|numeric|min:0',
            'common_area' => 'required|numeric|min:0',
            'area_sq_feet' => 'required|numeric|min:0',
            'owner_id' => 'required|exists:owners,id',
            'purchase_value' => 'required|numeric|min:0',
            'status' => 'required|string|in:VACANT,LEASED,MAINTENANCE,SOLD',
            'dewa_premise_no' => 'nullable|numeric|unique:properties,dewa_premise_no',
            'dewa_account_no' => 'nullable|numeric',
            'is_visible' => 'boolean',
            'deedFiles' => 'required|array|min:1',
            'deedFiles.*' => 'mimes:jpeg,png,jpg,pdf|max:10240',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $property = Property::create([
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
                'status' => $this->status,
                'dewa_premise_no' => $this->dewa_premise_no,
                'dewa_account_no' => $this->dewa_account_no,
                'is_visible' => $this->is_visible,
            ]);

            // Upload deed files
            if ($this->deedFiles && count($this->deedFiles) > 0) {
                foreach ($this->deedFiles as $file) {
                    $property->addMedia($file->getRealPath())
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('deeds', 'public');
                }
            }

            DB::commit();

            session()->flash('message', 'Property created successfully!');
            session()->flash('alert-type', 'success');

            return redirect()->route('properties.table');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Property creation error: ' . $e->getMessage());
            session()->flash('message', 'Error creating property: ' . $e->getMessage());
            session()->flash('alert-type', 'error');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.properties.create', [
            'owners' => Owner::orderBy('name')->get(),
            'propertyClasses' => ['1 BHK', '2 BHK', 'STUDIO', 'WAREHOUSE', 'OFFICE'],
            'propertyTypes' => ['Residential', 'Commercial', 'Land'],
            'mortgageStatuses' => ['None', 'Mortgaged'],
            'propertyStatuses' => ['VACANT', 'LEASED', 'MAINTENANCE'],
        ]);
    }
}
