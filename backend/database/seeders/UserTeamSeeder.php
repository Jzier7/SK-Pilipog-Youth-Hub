<?php

namespace Database\Seeders;

use App\Models\UserTeam;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run()
    {
        UserTeam::factory()->count(10)->create();
    }
}


