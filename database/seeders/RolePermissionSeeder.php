<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Permissions
        Permission::create(['name' => 'create-job']);
        Permission::create(['name' => 'edit-job']);
        Permission::create(['name' => 'delete-job']);
        Permission::create(['name' => 'apply-job']);
    
        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);
    
        // Assign Permissions to Roles
        $admin->givePermissionTo(['create-job', 'edit-job', 'delete-job']);
        $user->givePermissionTo('apply-job');
    }
}
