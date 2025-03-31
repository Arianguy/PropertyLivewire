<?php

namespace App\Livewire\Owners;

use App\Models\Owner;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $eid;
    public $eidexp = '';
    public $nationality;
    public $email;
    public $mobile;
    public $nakheelno = '';
    public $address;

    #[Rule('required|array|min:1')]
    public $passportFiles = [];

    #[Rule('required|array|min:1')]
    public $eidFiles = [];

    // Track files for drag and drop display
    public $uploadedPassports = false;
    public $uploadedEids = false;

    public function updatedPassportFiles()
    {
        $this->uploadedPassports = true;
    }

    public function updatedEidFiles()
    {
        $this->uploadedEids = true;
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'eid' => 'required|string|max:50|unique:owners,eid',
        'eidexp' => 'required|date|after:today',
        'nationality' => 'required|string|max:100',
        'email' => 'required|email|max:255',
        'mobile' => 'required|string|max:20',
        'nakheelno' => 'required|string|max:255',
        'address' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        $owner = Owner::create([
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
        foreach ($this->passportFiles as $file) {
            $owner->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('passports');
        }

        // Handle EID file uploads
        foreach ($this->eidFiles as $file) {
            $owner->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection('eids');
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Owner created successfully!'
        ]);

        return redirect()->route('owners.table');
    }

    public function render()
    {
        return view('livewire.owners.create');
    }
}
