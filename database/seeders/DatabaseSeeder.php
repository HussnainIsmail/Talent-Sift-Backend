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
       

        // Create the user with admin details
        $user = User::factory()->create([
            'name' => 'Muhammad Hussnaim',
            'role' => 'super-admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123321123'),
        ]);

        
    }
}
