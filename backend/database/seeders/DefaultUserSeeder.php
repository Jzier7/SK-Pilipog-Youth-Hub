<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Purok;
use Faker\Factory as Faker;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create an admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'birthdate' => $faker->date('Y-m-d'),
            'gender' => $faker->randomElement(['male', 'female']),
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'purok_id' => Purok::pluck('id')->random(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create a regular user
        User::create([
            'first_name' => 'User',
            'last_name' => 'Regular',
            'birthdate' => $faker->date('Y-m-d'),
            'gender' => $faker->randomElement(['male', 'female']),
            'email' => 'user@user.com',
            'username' => 'user',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'active_voter' => 1,
            'purok_id' => Purok::pluck('id')->random(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

