<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Event;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition()
    {
        $event = Event::inRandomOrder()->first();

        if (!$event) {
            return [];
        }

        $teams = Team::where('event_id', $event->id)->get();

        if ($teams->count() < 2) {
            return [];
        }

        $team1 = $teams->random();
        $team2 = $teams->where('id', '!=', $team1->id)->random();

        $statuses = ['pending', 'completed', 'canceled'];
        $status = $this->faker->randomElement($statuses);

        if ($status === 'completed') {
            $team1Score = $this->faker->numberBetween(0, 100);
            $team2Score = $this->faker->numberBetween(0, 100);
            $winner = $team1Score > $team2Score ? $team1->id : $team2->id;
            $loser = $team1Score < $team2Score ? $team1->id : $team2->id;
        } else {
            $team1Score = null;
            $team2Score = null;
            $winner = null;
            $loser = null;
        }

        $gameNames = [
            'First Round',
            'Quarter Finals',
            'Semi Finals',
            'Championship',
            'Finals',
            'Playoff Match',
            'Friendly Match',
            'Elimination Round',
        ];

        return [
            'name' => $this->faker->randomElement($gameNames),
            'event_id' => $event->id,
            'team1_id' => $team1->id,
            'team1_score' => $team1Score,
            'team2_id' => $team2->id,
            'team2_score' => $team2Score,
            'winner' => $winner,
            'loser' => $loser,
            'status' => $status,
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}

