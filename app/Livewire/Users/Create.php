<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];

    public function mount()
    {
        // Check permission
        if (!Auth::user()->hasRole('Super Admin') && !Auth::user()->can('create users')) {
            abort(403, 'Unauthorized action. You do not have permission to create users.');
        }
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        if (!empty($this->roles)) {
            $user->assignRole($this->roles);
        }

        $this->dispatch('notify', [
            'message' => 'User created successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('users.table');
    }

    public function render()
    {
        return view('livewire.users.create', [
            'availableRoles' => Role::orderBy('name')->get(),
        ]);
    }
}
