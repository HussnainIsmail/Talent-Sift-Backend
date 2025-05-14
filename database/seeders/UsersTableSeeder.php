<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Candidates
            [
                'name' => 'John Doe',
                'role' => 'candidate',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'role' => 'candidate',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'role' => 'candidate',
                'email' => 'mike.johnson@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emily Davis',
                'role' => 'candidate',
                'email' => 'emily.davis@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Robert Brown',
                'role' => 'candidate',
                'email' => 'robert.brown@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Recruiters
            [
                'name' => 'Alice Walker',
                'role' => 'recuriter',
                'email' => 'alice.walker@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David Miller',
                'role' => 'recuriter',
                'email' => 'david.miller@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laura Wilson',
                'role' => 'recuriter',
                'email' => 'laura.wilson@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'James Anderson',
                'role' => 'recuriter',
                'email' => 'james.anderson@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olivia Thomas',
                'role' => 'recuriter',
                'email' => 'olivia.thomas@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'api_token' => Str::random(60),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
