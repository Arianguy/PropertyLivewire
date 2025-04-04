<?php

namespace App\Livewire\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
    public $visa;
    public $visa_expiry;
    public $eid;
    public $eidexp;

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
            'email' => ['required', 'email', Rule::unique('tenants', 'email')->ignore($this->tenant->id)],
            'mobile' => 'required|string|min:10|max:15',
            'nationality' => 'required|string',
            'passport_no' => 'required|string|min:2',
            'passport_expiry' => 'required|date|after:today',
            'visa' => 'required|string|min:5|max:95',
            'visa_expiry' => 'required|date',
            'eid' => ['required', 'string', function ($attribute, $value, $fail) {
                // Debug output
                \Illuminate\Support\Facades\Log::info('Edit EID value being validated: "' . $value . '", length: ' . strlen($value) . ', is_numeric: ' . (is_numeric($value) ? 'true' : 'false'));

                // If already formatted, validate with regex
                if (preg_match('/^\d{3}-\d{4}-\d{7}-\d{1}$/', $value)) {
                    \Illuminate\Support\Facades\Log::info('Edit EID validation passed regex check');
                    return;
                }

                // If entered as plain number, validate length and format it
                if (preg_match('/^\d{15}$/', $value)) {
                    \Illuminate\Support\Facades\Log::info('Edit EID validation passed 15-digit check, formatting');
                    $this->eid = substr($value, 0, 3) . '-' .
                        substr($value, 3, 4) . '-' .
                        substr($value, 7, 7) . '-' .
                        substr($value, 14, 1);
                    return;
                }

                // Handle any other 15-digit numeric value
                if (strlen($value) == 15 && is_numeric($value)) {
                    \Illuminate\Support\Facades\Log::info('Edit EID validation passed numeric 15-char check, formatting');
                    $this->eid = substr($value, 0, 3) . '-' .
                        substr($value, 3, 4) . '-' .
                        substr($value, 7, 7) . '-' .
                        substr($value, 14, 1);
                    return;
                }

                // Try removing any non-numeric characters and check if we have 15 digits
                $numericOnly = preg_replace('/[^0-9]/', '', $value);
                if (strlen($numericOnly) == 15) {
                    \Illuminate\Support\Facades\Log::info('Edit EID validation: stripped non-numeric chars, now formatting');
                    $this->eid = substr($numericOnly, 0, 3) . '-' .
                        substr($numericOnly, 3, 4) . '-' .
                        substr($numericOnly, 7, 7) . '-' .
                        substr($numericOnly, 14, 1);
                    return;
                }

                \Illuminate\Support\Facades\Log::info('Edit EID validation failed all checks');
                $fail('The Emirates ID must be 15 digits or in the format xxx-xxxx-xxxxxxx-x.');
            }],
            'eidexp' => 'required|date',
            'passport_files' => [
                function ($attribute, $value, $fail) {
                    // Make files required if no existing files and no new uploads
                    if (empty($value) && count($this->passportMedia) === 0) {
                        $fail('At least one passport document is required.');
                    }
                },
                'nullable',
                'array',
            ],
            'passport_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
            'visa_files' => [
                function ($attribute, $value, $fail) {
                    // Make files required if no existing files and no new uploads
                    if (empty($value) && count($this->visaMedia) === 0) {
                        $fail('At least one visa document is required.');
                    }
                },
                'nullable',
                'array',
            ],
            'visa_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
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
        $this->passport_expiry = is_string($tenant->passport_expiry) ? $tenant->passport_expiry : $tenant->passport_expiry->format('Y-m-d');
        $this->visa = $tenant->visa;
        $this->visa_expiry = is_string($tenant->visa_expiry) ? $tenant->visa_expiry : $tenant->visa_expiry->format('Y-m-d');
        $this->eid = $tenant->eid;
        $this->eidexp = is_string($tenant->eidexp) ? $tenant->eidexp : $tenant->eidexp->format('Y-m-d');

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
        // Validate only the new files, not the entire array
        $this->validate([
            'passport_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
        ]);
    }

    public function updatedVisaFiles()
    {
        // Validate only the new files, not the entire array
        $this->validate([
            'visa_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
        ]);
    }

    public function uploadMultiple($propertyName, $files)
    {
        // Get current files
        $currentFiles = $this->{$propertyName} ?: [];

        // Add new files to the array
        foreach ($files as $file) {
            $currentFiles[] = $file;
        }

        // Update the property with all files
        $this->{$propertyName} = $currentFiles;
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

    public function getNationalityOptions()
    {
        return [
            'INDIAN' => 'INDIAN',
            'SRI LANKAN' => 'SRI LANKAN',
            'PAKISTANI' => 'PAKISTANI',
            'EMARATI' => 'EMARATI',
            'IRAQI' => 'IRAQI',
            'IRANI' => 'IRANI',
            'JORDAN' => 'JORDAN',
            'EGYPTIAN' => 'EGYPTIAN',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Convert fields to uppercase
            $name = strtoupper($this->name);
            $nationality = strtoupper($this->nationality);
            $passport_no = strtoupper($this->passport_no);
            $visa = strtoupper($this->visa);
            $eid = $this->eid;

            $this->tenant->update([
                'name' => $name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'nationality' => $nationality,
                'passport_no' => $passport_no,
                'passport_expiry' => $this->passport_expiry,
                'visa' => $visa,
                'visa_expiry' => $this->visa_expiry,
                'eid' => $eid,
                'eidexp' => $this->eidexp,
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
        return view('livewire.tenants.edit', [
            'nationalityOptions' => $this->getNationalityOptions(),
        ]);
    }

    public function removeFile($property, $index)
    {
        if (isset($this->{$property}[$index])) {
            // Remove the file at the specified index
            unset($this->{$property}[$index]);

            // Re-index the array to maintain sequential keys
            $this->{$property} = array_values($this->{$property});

            // Only validate if we have no files left and no existing media
            if (empty($this->{$property}) && empty($this->{$property . 'Media'})) {
                $this->validate([
                    $property => 'required|array|min:1',
                ]);
            }
        }
    }

    public function updated($propertyName)
    {
        // When files are updated, make sure arrays are properly initialized
        if ($propertyName === 'passport_files' || $propertyName === 'visa_files') {
            if (empty($this->{$propertyName})) {
                $this->{$propertyName} = [];
            }

            // Validate just this property
            $this->validateOnly($propertyName);
        }
    }
}
