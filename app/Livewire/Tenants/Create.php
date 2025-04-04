<?php

namespace App\Livewire\Tenants;

use Livewire\Component;
use App\Models\Tenant;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Illuminate\Validation\Rule as ValidationRule;

class Create extends Component
{
    use WithFileUploads;

    #[Rule('required|string|min:2')]
    public $name = '';

    #[Rule('required|email|unique:tenants,email')]
    public $email = '';

    #[Rule('required|string|min:10|max:15')]
    public $mobile = '';

    #[Rule('required|string')]
    public $nationality = '';

    #[Rule('required|string|min:2')]
    public $passport_no = '';

    #[Rule('required|date|after:today')]
    public $passport_expiry = '';

    #[Rule('required|string|min:5|max:95')]
    public $visa = '';

    #[Rule('required|date')]
    public $visa_expiry = '';

    public $eid = '';

    #[Rule('required|date')]
    public $eidexp = '';

    public $passport_files = [];
    public $visa_files = [];

    public function mount()
    {
        // Set default dates
        $this->passport_expiry = date('Y-m-d');
        $this->visa_expiry = date('Y-m-d');
        $this->eidexp = date('Y-m-d');
        $this->nationality = 'INDIAN'; // Set default nationality
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

    public function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:tenants,email',
            'mobile' => 'required|string|min:10|max:15',
            'nationality' => 'required|string',
            'passport_no' => 'required|string|min:2',
            'passport_expiry' => 'required|date|after:today',
            'visa' => 'required|string|min:5|max:95',
            'visa_expiry' => 'required|date',
            'eid' => ['required', 'string', function ($attribute, $value, $fail) {
                // Debug output
                \Illuminate\Support\Facades\Log::info('EID value being validated: "' . $value . '", length: ' . strlen($value) . ', is_numeric: ' . (is_numeric($value) ? 'true' : 'false'));

                // If already formatted, validate with regex
                if (preg_match('/^\d{3}-\d{4}-\d{7}-\d{1}$/', $value)) {
                    \Illuminate\Support\Facades\Log::info('EID validation passed regex check');
                    return;
                }

                // If entered as plain number, validate length and format it
                if (preg_match('/^\d{15}$/', $value)) {
                    \Illuminate\Support\Facades\Log::info('EID validation passed 15-digit check, formatting');
                    $this->eid = substr($value, 0, 3) . '-' .
                        substr($value, 3, 4) . '-' .
                        substr($value, 7, 7) . '-' .
                        substr($value, 14, 1);
                    return;
                }

                // Handle any other 15-digit numeric value
                if (strlen($value) == 15 && is_numeric($value)) {
                    \Illuminate\Support\Facades\Log::info('EID validation passed numeric 15-char check, formatting');
                    $this->eid = substr($value, 0, 3) . '-' .
                        substr($value, 3, 4) . '-' .
                        substr($value, 7, 7) . '-' .
                        substr($value, 14, 1);
                    return;
                }

                // Try removing any non-numeric characters and check if we have 15 digits
                $numericOnly = preg_replace('/[^0-9]/', '', $value);
                if (strlen($numericOnly) == 15) {
                    \Illuminate\Support\Facades\Log::info('EID validation: stripped non-numeric chars, now formatting');
                    $this->eid = substr($numericOnly, 0, 3) . '-' .
                        substr($numericOnly, 3, 4) . '-' .
                        substr($numericOnly, 7, 7) . '-' .
                        substr($numericOnly, 14, 1);
                    return;
                }

                \Illuminate\Support\Facades\Log::info('EID validation failed all checks');
                $fail('The Emirates ID must be 15 digits or in the format xxx-xxxx-xxxxxxx-x.');
            }],
            'eidexp' => 'required|date',
            'passport_files' => 'required|array|min:1',
            'passport_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
            'visa_files' => 'required|array|min:1',
            'visa_files.*' => 'file|mimes:jpeg,jpg,png,pdf|max:10240',
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

            $tenant = Tenant::create([
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

    public function removeFile($property, $index)
    {
        if (isset($this->{$property}[$index])) {
            // Remove the file at the specified index
            unset($this->{$property}[$index]);

            // Re-index the array to maintain sequential keys
            $this->{$property} = array_values($this->{$property});

            // Only validate if we have no files left
            if (empty($this->{$property})) {
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

    public function render()
    {
        return view('livewire.tenants.create', [
            'nationalityOptions' => $this->getNationalityOptions(),
        ]);
    }
}
