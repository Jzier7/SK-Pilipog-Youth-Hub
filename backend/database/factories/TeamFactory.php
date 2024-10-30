<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company,
            'event_id' => Event::all()->random()->id,
        ];
    }
}

