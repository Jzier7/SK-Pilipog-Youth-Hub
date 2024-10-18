<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurokFactory extends Factory
{
    public function definition(): array
    {
        static $purokCounter = 1;

        return [
            'name' => 'Purok ' . $purokCounter++,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

