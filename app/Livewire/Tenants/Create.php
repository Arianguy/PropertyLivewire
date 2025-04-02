<?php

namespace App\Livewire\Tenants;

use Livewire\Component;
use App\Models\Tenant;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;

class Create extends Component
{
    use WithFileUploads;

    #[Rule('required|string|min:2')]
    public $name = '';

    #[Rule('required|email|unique:tenants,email')]
    public $email = '';

    #[Rule('required|string|min:10')]
    public $mobile = '';

    #[Rule('required|string|min:2')]
    public $nationality = '';

    #[Rule('required|string|min:2')]
    public $passport_no = '';

    #[Rule('required|date')]
    public $passport_expiry = '';

    #[Rule('required|date')]
    public $visa_expiry = '';

    public $passport_files = [];
    public $visa_files = [];

    public function updatedPassportFiles()
    {
        // Method trigger when files are uploaded via file input
    }

    public function updatedVisaFiles()
    {
        // Method trigger when files are uploaded via file input
    }

    public function uploadMultiple($propertyName, $files)
    {
        foreach ($files as $file) {
            $this->{$propertyName}[] = $file;
        }
    }

    public function rules()
    {
        return [
            'passport_files' => 'nullable|array',
            'passport_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
            'visa_files' => 'nullable|array',
            'visa_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $tenant = Tenant::create([
                'name' => $this->name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'nationality' => $this->nationality,
                'passport_no' => $this->passport_no,
                'passport_expiry' => $this->passport_expiry,
                'visa_expiry' => $this->visa_expiry,
            ]);

            // Upload passport files
            if (!empty($this->passport_files)) {
                foreach ($this->passport_files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('tmp', $filename, 'public');

                    $media = $tenant->addMedia(storage_path('app/public/' . $path))
                        ->usingName($file->getClientOriginalName())
                        ->toMediaCollection('passport_files');

                    $media->setCustomProperty('type', 'passport');
                    $media->save();
                }
            }

            // Upload visa files
            if (!empty($this->visa_files)) {
                foreach ($this->visa_files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('tmp', $filename, 'public');

                    $media = $tenant->addMedia(storage_path('app/public/' . $path))
                        ->usingName($file->getClientOriginalName())
                        ->toMediaCollection('visa_files');

                    $media->setCustomProperty('type', 'visa');
                    $media->save();
                }
            }

            DB::commit();
            session()->flash('success', 'Tenant created successfully.');
            return redirect()->route('tenants.table');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating tenant: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tenants.create');
    }
}
