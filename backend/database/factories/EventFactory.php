<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'initiator_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'event_date' => $this->faker->date,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}


