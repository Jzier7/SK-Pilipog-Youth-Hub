<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'author_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'created_at' => $this->randomDate(),
            'updated_at' => now(),
        ];
    }

    private function randomDate()
    {
        // Generate a random number between 0 and 3
        $random = rand(0, 3);

        switch ($random) {
            case 0:
                return now(); // Now
            case 1:
                return now()->subWeek(); // One week ago
            case 2:
                return now()->subMonth(); // One month ago
            case 3:
                return now()->subYear(); // One year ago
            default:
                return now(); // Fallback to now
        }
    }
}

