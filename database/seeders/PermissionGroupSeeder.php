<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userManagement = Module::where('name', 'User Management')->first();
        $roleManagement = Module::where('name', 'Role Management')->first();
        $propertyManagement = Module::where('name', 'Property Management')->first();
        $settings = Module::where('name', 'Settings')->first();

        $groups = [
            // User Management Groups
            [
                'name' => 'User Access',
                'description' => 'User access and authentication permissions',
                'module_id' => $userManagement->id,
                'order' => 1,
            ],
            [
                'name' => 'User Management',
                'description' => 'User management and profile permissions',
                'module_id' => $userManagement->id,
                'order' => 2,
            ],

            // Role Management Groups
            [
                'name' => 'Role Access',
                'description' => 'Role access and viewing permissions',
                'module_id' => $roleManagement->id,
                'order' => 1,
            ],
            [
                'name' => 'Role Management',
                'description' => 'Role management and assignment permissions',
                'module_id' => $roleManagement->id,
                'order' => 2,
            ],

            // Property Management Groups
            [
                'name' => 'Property Access',
                'description' => 'Property viewing and access permissions',
                'module_id' => $propertyManagement->id,
                'order' => 1,
            ],
            [
                'name' => 'Property Management',
                'description' => 'Property management and editing permissions',
                'module_id' => $propertyManagement->id,
                'order' => 2,
            ],

            // Settings Groups
            [
                'name' => 'System Settings',
                'description' => 'System configuration and settings permissions',
                'module_id' => $settings->id,
                'order' => 1,
            ],
        ];

        foreach ($groups as $group) {
            PermissionGroup::create($group);
        }
    }
}
