<?php

namespace App\Livewire\Owners;

use App\Models\Owner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;

class Edit extends Component
{
    use WithFileUploads;

    public Owner $owner;

    public $name = '';
    public $eid = '';
    public $eidexp = '';
    public $nationality = '';
    public $email = '';
    public $mobile = '';
    public $nakheelno = '';
    public $address = '';

    #[Rule('nullable|array')]
    public $passportFiles = [];

    #[Rule('nullable|array')]
    public $eidFiles = [];

    // Track files for drag and drop display
    public $uploadedPassports = false;
    public $uploadedEids = false;

    public function mount(Owner $owner)
    {
        $this->owner = $owner;
        $this->name = $owner->name;
        $this->eid = $owner->eid;
        $this->eidexp = $owner->eidexp instanceof \DateTime ? $owner->eidexp->format('Y-m-d') : '';
        $this->nationality = $owner->nationality;
        $this->email = $owner->email;
        $this->mobile = $owner->mobile;
        $this->nakheelno = $owner->nakheelno;
        $this->address = $owner->address;
    }

    public function updatedPassportFiles()
    {
        $this->uploadedPassports = true;
    }

    public function updatedEidFiles()
    {
        $this->uploadedEids = true;
    }

    public function deleteMedia($mediaId)
    {
        try {
            $media = $this->owner->media()->findOrFail($mediaId);
            $media->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'File deleted successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error deleting file: ' . $e->getMessage()
            ]);
        }
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'eid' => ['required', 'string', 'max:255', 'unique:owners,eid,' . $this->owner->id],
            'eidexp' => ['required', 'date'],
            'nationality' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'nakheelno' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $this->validate();

        $this->owner->update([
            'name' => $this->name,
            'eid' => $this->eid,
            'eidexp' => $this->eidexp,
            'nationality' => $this->nationality,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'nakheelno' => $this->nakheelno,
            'address' => $this->address,
        ]);

        // Handle passport file uploads
        if (!empty($this->passportFiles)) {
            foreach ($this->passportFiles as $file) {
                $this->owner->addMedia($file->getRealPath())
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('passports');
            }
        }

        // Handle EID file uploads
        if (!empty($this->eidFiles)) {
            foreach ($this->eidFiles as $file) {
                $this->owner->addMedia($file->getRealPath())
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName())
                    ->toMediaCollection('eids');
            }
        }

        $this->dispatch('notify', [
            'message' => 'Owner updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('owners.table');
    }

    public function render()
    {
        return view('livewire.owners.edit', [
            'passportMedia' => $this->owner->getMedia('passports'),
            'eidMedia' => $this->owner->getMedia('eids')
        ]);
    }
}
