<?php

namespace Database\Seeders;

use App\Models\ForumPost;
use Illuminate\Database\Seeder;

class ForumPostSeeder extends Seeder
{
    public function run(): void
    {
        ForumPost::factory()->count(10)->create();
    }
}

