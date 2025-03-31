<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Access Permissions
            [
                'name' => 'view-users',
                'description' => 'View user list and details',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'User Access')->first()->id,
            ],
            [
                'name' => 'create-users',
                'description' => 'Create new users',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'User Management')->first()->id,
            ],
            [
                'name' => 'edit-users',
                'description' => 'Edit existing users',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'User Management')->first()->id,
            ],
            [
                'name' => 'delete-users',
                'description' => 'Delete users',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'User Management')->first()->id,
            ],

            // Role Access Permissions
            [
                'name' => 'view-roles',
                'description' => 'View role list and details',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Role Access')->first()->id,
            ],
            [
                'name' => 'create-roles',
                'description' => 'Create new roles',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Role Management')->first()->id,
            ],
            [
                'name' => 'edit-roles',
                'description' => 'Edit existing roles',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Role Management')->first()->id,
            ],
            [
                'name' => 'delete-roles',
                'description' => 'Delete roles',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Role Management')->first()->id,
            ],

            // Property Access Permissions
            [
                'name' => 'view-properties',
                'description' => 'View property list and details',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Property Access')->first()->id,
            ],
            [
                'name' => 'create-properties',
                'description' => 'Create new properties',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Property Management')->first()->id,
            ],
            [
                'name' => 'edit-properties',
                'description' => 'Edit existing properties',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Property Management')->first()->id,
            ],
            [
                'name' => 'delete-properties',
                'description' => 'Delete properties',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'Property Management')->first()->id,
            ],

            // Settings Permissions
            [
                'name' => 'view-settings',
                'description' => 'View system settings',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'System Settings')->first()->id,
            ],
            [
                'name' => 'edit-settings',
                'description' => 'Edit system settings',
                'guard_name' => 'web',
                'permission_group_id' => PermissionGroup::where('name', 'System Settings')->first()->id,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
