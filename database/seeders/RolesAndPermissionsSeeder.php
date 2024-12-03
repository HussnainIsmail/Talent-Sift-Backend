<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            'edit articles',
            'delete articles',
            'publish articles',
            'view articles'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->syncPermissions(['edit articles', 'delete articles']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->syncPermissions(['view articles']);

        // Output to confirm seeding
        $this->command->info('Roles and permissions have been seeded!');
    }
}
