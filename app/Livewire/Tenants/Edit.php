<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    use WithFileUploads;

    public Tenant $tenant;

    // Basic information
    public $name;
    public $email;
    public $mobile;
    public $nationality;
    public $passport_no;
    public $passport_expiry;
    public $visa_expiry;

    // Document uploads
    public $passport_files = [];
    public $visa_files = [];

    // Upload tracking
    public $uploadedPassports = false;
    public $uploadedVisas = false;

    public $passportMedia = [];
    public $visaMedia = [];

    public function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:tenants,email,' . $this->tenant->id,
            'mobile' => 'required|string|min:10',
            'nationality' => 'required|string|min:2',
            'passport_no' => 'required|string|min:2',
            'passport_expiry' => 'required|date',
            'visa_expiry' => 'required|date',
            'passport_files' => 'nullable|array',
            'passport_files.*' => 'file|mimes:jpeg,png,pdf|max:10240',
            'visa_files' => 'nullable|array',
            'visa_files.*' => 'file|mimes:jpeg,png,pdf|max:10240',
        ];
    }

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->name = $tenant->name;
        $this->email = $tenant->email;
        $this->mobile = $tenant->mobile;
        $this->nationality = $tenant->nationality;
        $this->passport_no = $tenant->passport_no;
        $this->passport_expiry = $tenant->passport_expiry instanceof \DateTime ? $tenant->passport_expiry->format('Y-m-d') : $tenant->passport_expiry;
        $this->visa_expiry = $tenant->visa_expiry instanceof \DateTime ? $tenant->visa_expiry->format('Y-m-d') : $tenant->visa_expiry;

        $this->loadMedia();
    }

    public function loadMedia()
    {
        $this->passportMedia = $this->tenant->getMedia('passport_files')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'url' => $this->tenant->getSecureMediaUrl($media),
                'thumb' => $this->tenant->getSecureMediaUrl($media, 'thumb'),
                'download' => $this->tenant->getSecureDownloadUrl($media),
            ];
        })->toArray();

        $this->visaMedia = $this->tenant->getMedia('visa_files')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'url' => $this->tenant->getSecureMediaUrl($media),
                'thumb' => $this->tenant->getSecureMediaUrl($media, 'thumb'),
                'download' => $this->tenant->getSecureDownloadUrl($media),
            ];
        })->toArray();
    }

    public function updatedPassportFiles()
    {
        $this->uploadedPassports = true;
    }

    public function updatedVisaFiles()
    {
        $this->uploadedVisas = true;
    }

    public function uploadMultiple($propertyName, $files)
    {
        foreach ($files as $file) {
            $this->{$propertyName}[] = $file;
        }

        if ($propertyName === 'passport_files') {
            $this->uploadedPassports = true;
        } elseif ($propertyName === 'visa_files') {
            $this->uploadedVisas = true;
        }
    }

    public function deleteMedia($mediaId)
    {
        $media = DB::table('media')->find($mediaId);

        if ($media) {
            $mediaItem = $this->tenant->media()->find($mediaId);
            if ($mediaItem) {
                $mediaItem->delete();
            }

            $this->loadMedia();
            session()->flash('message', 'Document deleted successfully!');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->tenant->update([
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

                    $media = $this->tenant->addMedia(storage_path('app/public/' . $path))
                        ->usingName($file->getClientOriginalName())
                        ->toMediaCollection('passport_files');

                    $media->setCustomProperty('type', 'passport');
                    $media->save();
                }
                $this->passport_files = [];
            }

            // Upload visa files
            if (!empty($this->visa_files)) {
                foreach ($this->visa_files as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('tmp', $filename, 'public');

                    $media = $this->tenant->addMedia(storage_path('app/public/' . $path))
                        ->usingName($file->getClientOriginalName())
                        ->toMediaCollection('visa_files');

                    $media->setCustomProperty('type', 'visa');
                    $media->save();
                }
                $this->visa_files = [];
            }

            $this->uploadedPassports = false;
            $this->uploadedVisas = false;

            DB::commit();

            $this->loadMedia();
            session()->flash('success', 'Tenant updated successfully!');

            return redirect()->route('tenants.table');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating tenant: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.tenants.edit');
    }
}
