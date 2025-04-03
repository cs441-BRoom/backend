<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'username' => 'john_doe',
                'password' => Hash::make('password'),
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'john@example.com',
                'auth_type' => 'local',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'jane_smith',
                'password' => Hash::make('password'),
                'firstname' => 'Jane',
                'lastname' => 'Smith',
                'email' => 'jane@example.com',
                'auth_type' => 'local',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'michael_brown',
                'password' => Hash::make('password'),
                'firstname' => 'Michael',
                'lastname' => 'Brown',
                'email' => 'michael@example.com',
                'auth_type' => 'local',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'emma_white',
                'password' => Hash::make('password'),
                'firstname' => 'Emma',
                'lastname' => 'White',
                'email' => 'emma@example.com',
                'auth_type' => 'local',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'lucas_black',
                'password' => Hash::make('password'),
                'firstname' => 'Lucas',
                'lastname' => 'Black',
                'email' => 'lucas@example.com',
                'auth_type' => 'local',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
