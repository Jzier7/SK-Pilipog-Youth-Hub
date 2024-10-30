<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTeamSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $teams = Team::all();

        if ($users->isEmpty() || $teams->isEmpty()) {
            $this->command->info('No users or teams available. Make sure to seed users and teams tables first.');
            return;
        }

        foreach ($users as $user) {
            $userTeams = $teams->random(rand(1, 3));

            foreach ($userTeams as $team) {
                DB::table('user_teams')->insert([
                    'player_id' => $user->id,
                    'team_id' => $team->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

