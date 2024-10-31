<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run()
    {
        $gamesToCreate = 10;

        for ($i = 0; $i < $gamesToCreate; $i++) {
            $game = Game::factory()->make();

            if ($game->event_id && $game->team1_id && $game->team2_id) {
                $game->save();
            }
        }
    }
}

