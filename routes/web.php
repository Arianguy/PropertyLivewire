<?php

use App\Livewire\Config\RolesTable;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;




Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Roles and Permissions Management
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/roles', App\Livewire\Roles\Table::class)->name('roles.table');
    Route::get('/roles/create', App\Livewire\Roles\Create::class)->name('roles.create');
    Route::get('/roles/{role}/edit', App\Livewire\Roles\Edit::class)->name('roles.edit');

    Route::get('/permissions', App\Livewire\Permissions\Table::class)->name('permissions.table');
    Route::get('/permissions/create', App\Livewire\Permissions\Create::class)->name('permissions.create');
    Route::get('/permissions/{permission}/edit', App\Livewire\Permissions\Edit::class)->name('permissions.edit');

    Route::get('/modules', App\Livewire\Modules\Table::class)->name('modules.table');
    Route::get('/modules/create', App\Livewire\Modules\Create::class)->name('modules.create');
    Route::get('/modules/{module}/edit', App\Livewire\Modules\Edit::class)->name('modules.edit');

    Route::get('/permission-groups', App\Livewire\PermissionGroups\Table::class)->name('permission-groups.table');
    Route::get('/permission-groups/create', App\Livewire\PermissionGroups\Create::class)->name('permission-groups.create');
    Route::get('/permission-groups/{permissionGroup}/edit', App\Livewire\PermissionGroups\Edit::class)->name('permission-groups.edit');

    // Owners Management
    Route::get('/owners', App\Livewire\Owners\Table::class)->name('owners.table');
    Route::get('/owners/create', App\Livewire\Owners\Create::class)->name('owners.create');
    Route::get('/owners/{owner}/edit', App\Livewire\Owners\Edit::class)->name('owners.edit');

    // Properties Management
    Route::get('/properties', App\Livewire\Properties\Table::class)->name('properties.table');
    Route::get('/properties/create', App\Livewire\Properties\Create::class)->name('properties.create');
    Route::get('/properties/{property}/edit', App\Livewire\Properties\Edit::class)->name('properties.edit');

    // Users Management
    Route::get('/users', App\Livewire\Users\Table::class)->name('users.table');
    Route::get('/users/create', App\Livewire\Users\Create::class)->name('users.create');
    Route::get('/users/{user}/edit', App\Livewire\Users\Edit::class)->name('users.edit');
});

// Media secure routes
Route::middleware(['auth', 'verified', App\Http\Middleware\ProtectMediaAccess::class])->group(function () {
    Route::get('/media/{id}', [App\Http\Controllers\MediaController::class, 'show'])->name('media.show');
    Route::get('/media/{id}/download', [App\Http\Controllers\MediaController::class, 'download'])->name('media.download');
    Route::get('/media/{id}/thumbnail/{conversion?}', [App\Http\Controllers\MediaController::class, 'thumbnail'])->name('media.thumbnail');
});

require __DIR__ . '/auth.php';
