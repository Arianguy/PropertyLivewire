<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\PermissionGroup;
use App\Models\Permission;

class ContractPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create the Contract Management module
        $module = Module::firstOrCreate(
            ['name' => 'Contract Management'],
            [
                'description' => 'Manage contracts and agreements',
                'icon' => 'document-text',
                'order' => 4
            ]
        );

        // Get or create the Contract Management permission group
        $contractManagementGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Contract Management'],
            [
                'description' => 'Contract management permissions',
                'module_id' => $module->id,
                'order' => 2
            ]
        );

        // Get or create the Contract Access permission group
        $contractAccessGroup = PermissionGroup::firstOrCreate(
            ['name' => 'Contract Access'],
            [
                'description' => 'Basic contract access permissions',
                'module_id' => $module->id,
                'order' => 1
            ]
        );

        // Update existing contract permissions to ensure they have the correct module_id and permission_group_id
        Permission::where('name', 'view contracts')->update([
            'module_id' => $module->id,
            'permission_group_id' => $contractAccessGroup->id
        ]);

        Permission::where('name', 'create contracts')->update([
            'module_id' => $module->id,
            'permission_group_id' => $contractManagementGroup->id
        ]);

        Permission::where('name', 'edit contracts')->update([
            'module_id' => $module->id,
            'permission_group_id' => $contractManagementGroup->id
        ]);

        Permission::where('name', 'delete contracts')->update([
            'module_id' => $module->id,
            'permission_group_id' => $contractManagementGroup->id
        ]);

        // Create or update the renew contracts permission
        Permission::firstOrCreate(
            ['name' => 'renew contracts'],
            [
                'guard_name' => 'web',
                'description' => 'Renew existing contracts',
                'module_id' => $module->id,
                'permission_group_id' => $contractManagementGroup->id
            ]
        );

        // Create or update the terminate contracts permission
        Permission::firstOrCreate(
            ['name' => 'terminate contracts'],
            [
                'guard_name' => 'web',
                'description' => 'Terminate existing contracts',
                'module_id' => $module->id,
                'permission_group_id' => $contractManagementGroup->id
            ]
        );

        $this->command->info('Contract permissions seeded successfully.');
    }
}
