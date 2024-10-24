<?php

namespace Database\Seeders;

use App\Models\ForumComment;
use Illuminate\Database\Seeder;

class ForumCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ForumComment::factory()->count(20)->create();
    }
}
