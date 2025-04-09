<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public function mount()
    {
        // Check if user has permission to view users
        if (!Auth::user()->hasRole('Super Admin') && !Auth::user()->can('view users')) {
            abort(403, 'Unauthorized action. You do not have permission to view users.');
        }
    }

    public function deleteUser(User $user)
    {
        // Check if user has permission to delete users
        if (!Auth::user()->hasRole('Super Admin') && !Auth::user()->can('delete users')) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'You do not have permission to delete users.'
            ]);
            return;
        }

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

    // Helper methods for the view to check permissions
    public function canEditUsers()
    {
        return Auth::user()->hasRole('Super Admin') || Auth::user()->can('edit users');
    }

    public function canDeleteUsers()
    {
        return Auth::user()->hasRole('Super Admin') || Auth::user()->can('delete users');
    }

    public function canCreateUsers()
    {
        return Auth::user()->hasRole('Super Admin') || Auth::user()->can('create users');
    }

    public function render()
    {
        return view('livewire.users.table', [
            'users' => User::with('roles')->paginate(10),
            'canCreate' => $this->canCreateUsers(),
            'canEdit' => $this->canEditUsers(),
            'canDelete' => $this->canDeleteUsers(),
        ]);
    }
}
