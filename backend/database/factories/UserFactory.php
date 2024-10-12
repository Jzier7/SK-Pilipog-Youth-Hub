<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Predefined purok values.
     */
    private array $puroks = [
        'Purok 1',
        'Purok 2',
        'Purok 3',
        'Purok 4',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->lastName,
            'last_name' => $this->faker->lastName,
            'birthdate' => $this->faker->date('Y-m-d'),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'purok' => $this->faker->randomElement($this->puroks),
            'active_voter' => $this->faker->boolean,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

