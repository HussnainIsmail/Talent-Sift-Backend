<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the 'owner' role if it doesn't exist
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);

        // Create the user with admin details
        $user = User::factory()->create([
            'name' => 'Muhammad Hussnaim',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123321123'),
        ]);

        // Assign the 'owner' role to the user
        $user->assignRole($ownerRole);
    }
}
