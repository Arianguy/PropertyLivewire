<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PaymentPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create the Payments module
        $module = Module::firstOrCreate(
            ['name' => 'Payments'],
            [
                'description' => 'Payment management module',
                'icon' => 'currency-dollar',
            ]
        );

        // Create Payment Management permission group if it doesn't exist
        $paymentGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Payment Management'],
            [
                'description' => 'Permissions related to payment management',
                'module_id' => $module->id,
            ]
        );

        $permissions = [
            [
                'name' => 'view payments',
                'description' => 'View payment list and details',
                'guard_name' => 'web',
                'permission_group_id' => $paymentGroup->id,
                'module_id' => $module->id,
            ],
            [
                'name' => 'create payments',
                'description' => 'Create new payments',
                'guard_name' => 'web',
                'permission_group_id' => $paymentGroup->id,
                'module_id' => $module->id,
            ],
            [
                'name' => 'edit payments',
                'description' => 'Edit existing payments',
                'guard_name' => 'web',
                'permission_group_id' => $paymentGroup->id,
                'module_id' => $module->id,
            ],
            [
                'name' => 'delete payments',
                'description' => 'Delete payments',
                'guard_name' => 'web',
                'permission_group_id' => $paymentGroup->id,
                'module_id' => $module->id,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
