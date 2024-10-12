<?php

namespace Database\Factories;

use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ForumPostFactory extends Factory
{
    protected $model = ForumPost::class;

    public function definition(): array
    {
        return [
            'author_id' => User::inRandomOrder()->first()->id,
            'post' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}


