<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Make sure to import your User model
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create Admin
        User::create([
            'first_name' => 'Admin',
            'middle_name' => 'User',
            'last_name' => 'Test',
            'birthdate' => '1990-01-01',
            'gender' => 'Female',
            'purok' => '2',
            'active_voter' => 1,
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role_id' => 2,
        ]);

        // Create Regular User
        User::create([
            'first_name' => 'Regular',
            'middle_name' => 'User',
            'last_name' => 'Example',
            'birthdate' => '1995-01-01',
            'gender' => 'Male',
            'purok' => '3',
            'active_voter' => 1,
            'email' => 'user@example.com',
            'username' => 'user',
            'password' => Hash::make('user123'),
            'role_id' => 3,
        ]);

        // Create Guest
        User::create([
            'first_name' => 'Guest',
            'middle_name' => '',
            'last_name' => 'User',
            'birthdate' => '2000-01-01',
            'gender' => 'Other',
            'purok' => '4',
            'active_voter' => 2,
            'email' => 'guest@example.com',
            'username' => 'guest',
            'password' => Hash::make('guest123'),
            'role_id' => 4,
        ]);
    }
}

