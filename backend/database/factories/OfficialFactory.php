<?php

namespace Database\Factories;

use App\Models\Official;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficialFactory extends Factory
{
    protected $model = Official::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'position' => $this->faker->jobTitle,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}


