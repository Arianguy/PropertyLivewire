<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public function deleteUser(User $user)
    {
        // Prevent deleting yourself
        if (Auth::id() === $user->id) {
            $this->addError('delete', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'User deleted successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.users.table', [
            'users' => User::with('roles')->paginate(10),
        ]);
    }
}
