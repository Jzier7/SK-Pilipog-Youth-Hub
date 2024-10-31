<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Basketball - U18',
            'Basketball - U25',
            'Volleyball - U18',
            'Volleyball - U25',
            'Football - U18',
            'Badminton - Open',
            'Table Tennis - U12',
            'Softball - U30',
            'Track and Field - U20',
        ];

        return [
            'name' => $this->faker->randomElement($categories),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

