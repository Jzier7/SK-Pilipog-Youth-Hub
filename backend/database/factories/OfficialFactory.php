<?php

namespace Database\Factories;

use App\Models\Official;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficialFactory extends Factory
{
    protected $model = Official::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'position' => $this->faker->jobTitle,
            'term_id' => Term::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

