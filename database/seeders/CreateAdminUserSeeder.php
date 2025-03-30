<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions grouped by module
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users',  // Overall user management

            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage roles',  // Overall role management

            // Permission Management
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            'manage permissions',  // Overall permission management

            // Property Management
            'view property',
            'create property',
            'edit property',
            'delete property',
            'manage property',  // Overall property management

            // Contract Management
            'view contract',
            'create contract',
            'edit contract',
            'delete contract',
            'manage contracts',  // Overall contract management

            // Tenant Management
            'view tenants',
            'create tenants',
            'edit tenants',
            'delete tenants',
            'manage tenants',    // Overall tenant management

            // Add other permissions as needed
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->where('guard_name', 'web')->exists()) {
				Permission::create(['name' => $permission, 'guard_name' => 'web']);
			}

        }

        // Create roles and assign permissions

        // 1. Super Admin
		$superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // 2. Owner
        $ownerRole = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);

        // Assign existing permissions to roles
        $ownerRole->givePermissionTo([
            'view property',
            'view contract',
            'view tenants'
        ]);

        // 3. Accountant
        $accountantRole = Role::firstOrCreate(['name' => 'Accountant', 'guard_name' => 'web']);
        $accountantRole->givePermissionTo([
            'view property',
            'create property',
            'view contract',
            'create contract',
            'view tenants'
        ]);

        // 4. Broker
        $agentRole = Role::firstOrCreate(['name' => 'Broker', 'guard_name' => 'web']);
        $agentRole->givePermissionTo([
            'view property',
            'create contract',
			'view contract',
            'view tenants',
            'create tenants',
            'edit tenants'
        ]);

        // 5. Tenant
        $tenantRole = Role::firstOrCreate(['name' => 'Tenant', 'guard_name' => 'web']);
        // No specific permissions for tenants in the admin system

        // Assign Super Admin role to specific user
        $user = User::find(1); // Change this to your user ID
        if ($user) {
            $user->assignRole('Super Admin');
        }
    }
}
