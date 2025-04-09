<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public User $user;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];

    public function mount(User $user)
    {
        // Check permission
        if (!Auth::user()->hasRole('Super Admin') && !Auth::user()->can('edit users')) {
            abort(403, 'Unauthorized action. You do not have permission to edit users.');
        }

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = $user->roles->pluck('name')->toArray();
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);

        // Directly sync role names
        $this->user->syncRoles($this->roles);

        $this->dispatch('notify', [
            'message' => 'User updated successfully.',
            'type' => 'success',
        ]);

        return redirect()->route('users.table');
    }

    public function render()
    {
        return view('livewire.users.edit', [
            'availableRoles' => Role::orderBy('name')->get(),
        ]);
    }
}
