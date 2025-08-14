<?php

namespace App\Livewire\Properties;

use App\Models\Property;
use App\Models\Owner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    use WithFileUploads;

    public Property $property;

    // Basic Information
    public $name;
    public $class;
    public $type;

    // Property Details
    public $purchase_date;
    public $title_deed_no;
    public $mortgage_status;

    // Location Information
    public $community;
    public $plot_no;
    public $bldg_no;
    public $bldg_name;
    public $property_no;
    public $floor_detail;

    // Area Information
    public $suite_area;
    public $balcony_area;
    public $area_sq_mter;
    public $common_area;
    public $area_sq_feet;

    // Financial Information
    public $owner_id;
    public $purchase_value;
    public $status;
    public $dewa_premise_no;
    public $dewa_account_no;
    public $is_visible;

    // Media
    public $deedMedia = [];
    public $deedFiles = [];
    public $uploadedDeeds = false;

    public function mount($property)
    {
        $this->property = $property;

        // Load property details
        $this->name = $property->name;
        $this->class = $property->class;
        $this->type = $property->type;
        $this->purchase_date = $property->purchase_date ? $property->purchase_date->format('Y-m-d') : null;
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
        $this->status = $property->status;
        $this->dewa_premise_no = $property->dewa_premise_no;
        $this->dewa_account_no = $property->dewa_account_no;
        $this->is_visible = $property->is_visible;

        // Load media
        $this->loadMedia();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:properties,name,' . $this->property->id,
            'class' => 'required|string',
            'type' => 'required|string',
            'purchase_date' => 'required|date',
            'title_deed_no' => 'required|string|max:255|unique:properties,title_deed_no,' . $this->property->id,
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
            'dewa_premise_no' => 'nullable|numeric|unique:properties,dewa_premise_no,' . $this->property->id,
            'dewa_account_no' => 'nullable|numeric',
            'is_visible' => 'boolean',
            'deedFiles' => 'nullable|array',
            'deedFiles.*' => 'mimes:jpeg,png,jpg,pdf|max:10240',
        ];
    }

    public function loadMedia()
    {
        $this->deedMedia = $this->property->getMedia('deeds');
    }

    public function updatedDeedFiles()
    {
        $this->uploadedDeeds = true;
    }

    public function deleteMedia($mediaId)
    {
        $media = Media::find($mediaId);

        if ($media) {
            $media->delete();
            $this->loadMedia();
            session()->flash('message', 'File deleted successfully!');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

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
                'status' => $this->status,
                'dewa_premise_no' => $this->dewa_premise_no,
                'dewa_account_no' => $this->dewa_account_no,
                'is_visible' => $this->is_visible,
            ]);

            // Upload deed files if any
            if ($this->deedFiles && count($this->deedFiles) > 0) {
                foreach ($this->deedFiles as $file) {
                    $this->property->addMedia($file->getRealPath())
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('deeds', 'public');
                }
            }

            DB::commit();

            session()->flash('message', 'Property updated successfully!');
            session()->flash('alert-type', 'success');

            return redirect()->route('properties.table');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Property update error: ' . $e->getMessage());
            session()->flash('message', 'Error updating property: ' . $e->getMessage());
            session()->flash('alert-type', 'error');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.properties.edit', [
            'owners' => Owner::orderBy('name')->get(),
            'propertyClasses' => ['1 BHK', '2 BHK', 'STUDIO', 'WAREHOUSE', 'OFFICE'],
            'propertyTypes' => ['Residential', 'Commercial', 'Land'],
            'mortgageStatuses' => ['None', 'Mortgaged'],
            'propertyStatuses' => ['VACANT', 'LEASED', 'MAINTENANCE'],
        ]);
    }
}
