<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables that are referenced by foreign key constraints
        Permission::truncate();

        // Enable foreign key checks again
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Permissions
        $permissions = [
            'create-job',
            'edit-job',
            'delete-job',
            'apply-job',
            'show-users',
            'edit-user',
            'create-role',
            'edit-role',
            'show-roles',
            'create-permission',
            'edit-permission',
            'show-permissions',
            'register-company',
            'show-companies',
            'show-resumes'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $superadmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Assign Permissions to Roles
        $superadmin->givePermissionTo($permissions);
        $admin->givePermissionTo([
            'create-job', 'edit-job', 'delete-job', 'show-users', 'edit-user',
            'create-role', 'edit-role', 'show-roles', 'create-permission', 'edit-permission',
            'show-permissions'
        ]);
        $user->givePermissionTo([
            'apply-job'
        ]);
    }
}
