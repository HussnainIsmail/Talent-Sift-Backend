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
        // Create Permissions
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'post job']);
        Permission::create(['name' => 'view jobs']);
        Permission::create(['name' => 'upload cv']);
        Permission::create(['name' => 'delete jobs']);
        Permission::create(['name' => 'view profile']);
        Permission::create(['name' => 'edit profile']);

        // Create Roles and Assign Permissions

        // Super Admin (Owner)
        $owner = Role::create(['name' => 'owner']);
        $owner->givePermissionTo(Permission::all());

        // Admin (can't remove super admin)
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['post job', 'view jobs', 'delete jobs']);

        // Sub Admin (can post and delete jobs)
        $subAdmin = Role::create(['name' => 'sub-admin']);
        $subAdmin->givePermissionTo(['post job', 'view jobs', 'delete jobs']);

        // Regular User
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo(['view profile', 'edit profile']);

    
    }
}
