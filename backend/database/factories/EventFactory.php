<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $names = [
            '1 Day League',
            'Inter Barangay',
            'Annual Sports Fest',
            'Purok Festival',
            'Youth Summit',
            'Health Awareness Campaign'
        ];

        return [
            'name' => $this->faker->randomElement($names),
            'category_id' => Category::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

