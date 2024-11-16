<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            PurokSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            AnnouncementSeeder::class,
            ForumPostSeeder::class,
            ForumCommentSeeder::class,
            EventSeeder::class,
            TermSeeder::class,
            PositionSeeder::class,
            OfficialSeeder::class,
            TeamSeeder::class,
            GameSeeder::class,
            DefaultUserSeeder::class,
            MessageSeeder::class,
        ]);

    }
}
