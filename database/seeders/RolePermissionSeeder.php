<?php

namespace Database\Seeders;

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
        Permission::truncate();
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
            'show-resumes',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $recruiter = Role::create(['name' => 'recruiter']);
        $user = Role::create(['name' => 'candidate']);

        // Assign Permissions
        $admin->givePermissionTo([
            'create-job',
            'edit-job',
            'delete-job',
            'show-users',
            'edit-user',
            'create-role',
            'edit-role',
            'show-roles',
            'create-permission',
            'edit-permission',
            'show-permissions',
            'show-companies',
            'show-resumes'
        ]);

        $recruiter->givePermissionTo([
            'create-job',
            'edit-job',
            'delete-job',
            'register-company',
            'show-companies',
            'show-resumes'
        ]);

        $user->givePermissionTo([
            'apply-job'
        ]);
    }
}
