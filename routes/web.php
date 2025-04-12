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

// All routes requiring authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Add diagnostic route
    Route::get('/diagnose-permissions', function () {
        $user = auth()->user();
        $permission = \App\Models\Permission::where('name', 'view tenants')->first();

        return [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
            'direct_permissions' => $user->getDirectPermissions()->pluck('name')->toArray(),
            'permission_via_roles' => $user->getPermissionsViaRoles()->pluck('name')->toArray(),
            'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'roles' => $user->roles()->pluck('name')->toArray(),
            'permission_check' => [
                'can_view_tenants' => $user->can('view tenants'),
                'has_permission_to' => $user->hasPermissionTo('view tenants'),
                'has_direct_permission' => $user->hasDirectPermission('view tenants'),
            ],
            'view_tenants_permission' => $permission ? [
                'id' => $permission->id,
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'module_id' => $permission->module_id,
                'permission_group_id' => $permission->permission_group_id,
            ] : null,
        ];
    })->middleware(['auth', 'verified']);

    // Roles Management
    Route::get('/roles', App\Livewire\Roles\Table::class)
        ->middleware('role_or_permission:Super Admin|view roles')
        ->name('roles.table');
    Route::get('/roles/create', App\Livewire\Roles\Create::class)
        ->middleware('role_or_permission:Super Admin|create roles')
        ->name('roles.create');
    Route::get('/roles/{role}/edit', App\Livewire\Roles\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit roles')
        ->name('roles.edit');

    // Permissions Management
    Route::get('/permissions', App\Livewire\Permissions\Table::class)
        ->middleware('role_or_permission:Super Admin|view permissions')
        ->name('permissions.table');
    Route::get('/permissions/create', App\Livewire\Permissions\Create::class)
        ->middleware('role_or_permission:Super Admin|create permissions')
        ->name('permissions.create');
    Route::get('/permissions/{permission}/edit', App\Livewire\Permissions\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit permissions')
        ->name('permissions.edit');

    // Modules Management
    Route::get('/modules', App\Livewire\Modules\Table::class)
        ->middleware('role_or_permission:Super Admin|view modules')
        ->name('modules.table');
    Route::get('/modules/create', App\Livewire\Modules\Create::class)
        ->middleware('role_or_permission:Super Admin|create modules')
        ->name('modules.create');
    Route::get('/modules/{module}/edit', App\Livewire\Modules\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit modules')
        ->name('modules.edit');

    // Permission Groups Management
    Route::get('/permission-groups', App\Livewire\PermissionGroups\Table::class)
        ->middleware('role_or_permission:Super Admin|view permission groups')
        ->name('permission-groups.table');
    Route::get('/permission-groups/create', App\Livewire\PermissionGroups\Create::class)
        ->middleware('role_or_permission:Super Admin|create permission groups')
        ->name('permission-groups.create');
    Route::get('/permission-groups/{permissionGroup}/edit', App\Livewire\PermissionGroups\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit permission groups')
        ->name('permission-groups.edit');

    // Users Management
    Route::get('/users', App\Livewire\Users\Table::class)
        ->middleware('role_or_permission:Super Admin|view users')
        ->name('users.table');
    Route::get('/users/create', App\Livewire\Users\Create::class)
        ->middleware('role_or_permission:Super Admin|create users')
        ->name('users.create');
    Route::get('/users/{user}/edit', App\Livewire\Users\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit users')
        ->name('users.edit');

    // MANAGEMENT SECTION

    // Owners Management
    Route::get('/owners', App\Livewire\Owners\Table::class)
        ->middleware('role_or_permission:Super Admin|view owners')
        ->name('owners.table');
    Route::get('/owners/create', App\Livewire\Owners\Create::class)
        ->middleware('role_or_permission:Super Admin|create owners')
        ->name('owners.create');
    Route::get('/owners/{owner}/edit', App\Livewire\Owners\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit owners')
        ->name('owners.edit');

    // Properties Management
    Route::get('/properties', App\Livewire\Properties\Table::class)
        ->middleware('role_or_permission:Super Admin|view properties')
        ->name('properties.table');
    Route::get('/properties/create', App\Livewire\Properties\Create::class)
        ->middleware('role_or_permission:Super Admin|create properties')
        ->name('properties.create');
    Route::get('/properties/{property}/edit', App\Livewire\Properties\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit properties')
        ->name('properties.edit');

    // Tenant Management
    Route::get('/tenants', App\Livewire\Tenants\Table::class)
        ->middleware(['auth', 'verified'])
        ->name('tenants.table');
    Route::get('/tenants/create', App\Livewire\Tenants\Create::class)
        ->middleware('role_or_permission:Super Admin|create tenants')
        ->name('tenants.create');
    Route::get('/tenants/{tenant}/edit', App\Livewire\Tenants\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit tenants')
        ->name('tenants.edit');

    // CONTRACTS SECTION

    // Contracts Management
    Route::get('/contracts', App\Livewire\Contracts\Table::class)
        ->middleware('role_or_permission:Super Admin|view contracts')
        ->name('contracts.table');
    Route::get('/contracts/create', App\Livewire\Contracts\Create::class)
        ->middleware('role_or_permission:Super Admin|create contracts')
        ->name('contracts.create');
    Route::get('/contracts/renewal-list', App\Livewire\Contracts\RenewalList::class)
        ->middleware('role_or_permission:Super Admin|view contracts')
        ->name('contracts.renewal-list');
    Route::get('/contracts/{contract}/edit', App\Livewire\Contracts\Edit::class)
        ->middleware('role_or_permission:Super Admin|edit contracts')
        ->name('contracts.edit');
    Route::get('/contracts/{contract}', App\Livewire\Contracts\Show::class)
        ->middleware('role_or_permission:Super Admin|view contracts')
        ->name('contracts.show');
    Route::get('/contracts/{contract}/renew', App\Livewire\Contracts\Renew::class)
        ->middleware('role_or_permission:Super Admin|edit contracts')
        ->name('contracts.renew');
    Route::get('/contracts/{contract}/terminate', App\Livewire\Contracts\Terminate::class)
        ->middleware('role_or_permission:Super Admin|edit contracts')
        ->name('contracts.terminate');

    // Receipts routes
    Route::get('/receipts', [App\Http\Controllers\ReceiptsController::class, 'index'])->name('receipts.index');
    Route::get('/contracts/{contract}/receipts/create', [App\Http\Controllers\ReceiptsController::class, 'create'])->name('receipts.create');
    Route::get('/contracts/{contract}/receipts', [App\Http\Controllers\ReceiptsController::class, 'listByContract'])->name('receipts.list-by-contract');
});

// Media secure routes
Route::middleware(['auth', 'verified', App\Http\Middleware\ProtectMediaAccess::class])->group(function () {
    Route::get('/media/{id}', [App\Http\Controllers\MediaController::class, 'show'])->name('media.show');
    Route::get('/media/{id}/download', [App\Http\Controllers\MediaController::class, 'download'])->name('media.download');
    Route::get('/media/{id}/thumbnail/{conversion?}', [App\Http\Controllers\MediaController::class, 'thumbnail'])->name('media.thumbnail');
});

require __DIR__ . '/auth.php';
