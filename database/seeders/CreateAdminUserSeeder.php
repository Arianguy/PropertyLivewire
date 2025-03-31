<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Module;
use App\Models\PermissionGroup;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Modules
        $userManagement = Module::firstOrCreate(['name' => 'User Management'], [
            'description' => 'Manage users and their access',
            'icon' => 'users',
            'order' => 1,
        ]);

        $roleManagement = Module::firstOrCreate(['name' => 'Role Management'], [
            'description' => 'Manage roles and permissions',
            'icon' => 'shield-check',
            'order' => 2,
        ]);

        $propertyManagement = Module::firstOrCreate(['name' => 'Property Management'], [
            'description' => 'Manage properties and listings',
            'icon' => 'building-office',
            'order' => 3,
        ]);

        $contractManagement = Module::firstOrCreate(['name' => 'Contract Management'], [
            'description' => 'Manage contracts and agreements',
            'icon' => 'document-text',
            'order' => 4,
        ]);

        $tenantManagement = Module::firstOrCreate(['name' => 'Tenant Management'], [
            'description' => 'Manage tenants and their information',
            'icon' => 'user-group',
            'order' => 5,
        ]);

        // Create Permission Groups
        $userAccess = PermissionGroup::firstOrCreate(['name' => 'User Access'], [
            'description' => 'Basic user access permissions',
            'module_id' => $userManagement->id,
            'order' => 1,
        ]);

        $userManagementGroup = PermissionGroup::firstOrCreate(['name' => 'User Management'], [
            'description' => 'User management permissions',
            'module_id' => $userManagement->id,
            'order' => 2,
        ]);

        $roleAccess = PermissionGroup::firstOrCreate(['name' => 'Role Access'], [
            'description' => 'Basic role access permissions',
            'module_id' => $roleManagement->id,
            'order' => 1,
        ]);

        $roleManagementGroup = PermissionGroup::firstOrCreate(['name' => 'Role Management'], [
            'description' => 'Role management permissions',
            'module_id' => $roleManagement->id,
            'order' => 2,
        ]);

        $propertyAccess = PermissionGroup::firstOrCreate(['name' => 'Property Access'], [
            'description' => 'Basic property access permissions',
            'module_id' => $propertyManagement->id,
            'order' => 1,
        ]);

        $propertyManagementGroup = PermissionGroup::firstOrCreate(['name' => 'Property Management'], [
            'description' => 'Property management permissions',
            'module_id' => $propertyManagement->id,
            'order' => 2,
        ]);

        $contractAccess = PermissionGroup::firstOrCreate(['name' => 'Contract Access'], [
            'description' => 'Basic contract access permissions',
            'module_id' => $contractManagement->id,
            'order' => 1,
        ]);

        $contractManagementGroup = PermissionGroup::firstOrCreate(['name' => 'Contract Management'], [
            'description' => 'Contract management permissions',
            'module_id' => $contractManagement->id,
            'order' => 2,
        ]);

        $tenantAccess = PermissionGroup::firstOrCreate(['name' => 'Tenant Access'], [
            'description' => 'Basic tenant access permissions',
            'module_id' => $tenantManagement->id,
            'order' => 1,
        ]);

        $tenantManagementGroup = PermissionGroup::firstOrCreate(['name' => 'Tenant Management'], [
            'description' => 'Tenant management permissions',
            'module_id' => $tenantManagement->id,
            'order' => 2,
        ]);

        // Create Permissions
        $permissions = [
            // User Management Permissions
            ['name' => 'view users', 'description' => 'View user list and details', 'permission_group_id' => $userAccess->id],
            ['name' => 'create users', 'description' => 'Create new users', 'permission_group_id' => $userManagementGroup->id],
            ['name' => 'edit users', 'description' => 'Edit existing users', 'permission_group_id' => $userManagementGroup->id],
            ['name' => 'delete users', 'description' => 'Delete users', 'permission_group_id' => $userManagementGroup->id],

            // Role Management Permissions
            ['name' => 'view roles', 'description' => 'View role list and details', 'permission_group_id' => $roleAccess->id],
            ['name' => 'create roles', 'description' => 'Create new roles', 'permission_group_id' => $roleManagementGroup->id],
            ['name' => 'edit roles', 'description' => 'Edit existing roles', 'permission_group_id' => $roleManagementGroup->id],
            ['name' => 'delete roles', 'description' => 'Delete roles', 'permission_group_id' => $roleManagementGroup->id],

            // Property Management Permissions
            ['name' => 'view properties', 'description' => 'View property list and details', 'permission_group_id' => $propertyAccess->id],
            ['name' => 'create properties', 'description' => 'Create new properties', 'permission_group_id' => $propertyManagementGroup->id],
            ['name' => 'edit properties', 'description' => 'Edit existing properties', 'permission_group_id' => $propertyManagementGroup->id],
            ['name' => 'delete properties', 'description' => 'Delete properties', 'permission_group_id' => $propertyManagementGroup->id],

            // Contract Management Permissions
            ['name' => 'view contracts', 'description' => 'View contract list and details', 'permission_group_id' => $contractAccess->id],
            ['name' => 'create contracts', 'description' => 'Create new contracts', 'permission_group_id' => $contractManagementGroup->id],
            ['name' => 'edit contracts', 'description' => 'Edit existing contracts', 'permission_group_id' => $contractManagementGroup->id],
            ['name' => 'delete contracts', 'description' => 'Delete contracts', 'permission_group_id' => $contractManagementGroup->id],

            // Tenant Management Permissions
            ['name' => 'view tenants', 'description' => 'View tenant list and details', 'permission_group_id' => $tenantAccess->id],
            ['name' => 'create tenants', 'description' => 'Create new tenants', 'permission_group_id' => $tenantManagementGroup->id],
            ['name' => 'edit tenants', 'description' => 'Edit existing tenants', 'permission_group_id' => $tenantManagementGroup->id],
            ['name' => 'delete tenants', 'description' => 'Delete tenants', 'permission_group_id' => $tenantManagementGroup->id],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'description' => $permission['description'],
                    'permission_group_id' => $permission['permission_group_id'],
                ]
            );
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin'], [
            'description' => 'Full system access',
        ]);

        $owner = Role::firstOrCreate(['name' => 'Owner'], [
            'description' => 'Property owner access',
        ]);

        $accountant = Role::firstOrCreate(['name' => 'Accountant'], [
            'description' => 'Financial management access',
        ]);

        $broker = Role::firstOrCreate(['name' => 'Broker'], [
            'description' => 'Property listing and management access',
        ]);

        $tenant = Role::firstOrCreate(['name' => 'Tenant'], [
            'description' => 'Tenant access',
        ]);

        // Assign Permissions to Roles
        $superAdmin->givePermissionTo(Permission::all());

        $owner->givePermissionTo([
            'view properties',
            'edit properties',
            'view contracts',
            'edit contracts',
            'view tenants',
            'edit tenants',
        ]);

        $accountant->givePermissionTo([
            'view properties',
            'view contracts',
            'edit contracts',
            'view tenants',
        ]);

        $broker->givePermissionTo([
            'view properties',
            'create properties',
            'edit properties',
            'view contracts',
            'create contracts',
            'edit contracts',
            'view tenants',
            'create tenants',
            'edit tenants',
        ]);

        $tenant->givePermissionTo([
            'view properties',
            'view contracts',
            'view tenants',
        ]);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'savio@ashtelgroup.com'],
            [
                'name' => 'Savio Fernandes',
                'password' => Hash::make('password'),
            ]
        );

        // Assign Super Admin Role to Admin User
        $admin->assignRole('Super Admin');
    }
}
