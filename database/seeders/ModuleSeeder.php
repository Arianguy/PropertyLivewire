<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'User Management',
                'description' => 'Manage users and their roles',
                'icon' => 'users',
                'order' => 1,
            ],
            [
                'name' => 'Role Management',
                'description' => 'Manage roles and permissions',
                'icon' => 'shield-check',
                'order' => 2,
            ],
            [
                'name' => 'Property Management',
                'description' => 'Manage properties and listings',
                'icon' => 'home',
                'order' => 3,
            ],
            [
                'name' => 'Settings',
                'description' => 'System settings and configurations',
                'icon' => 'cog',
                'order' => 4,
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
