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
        $team1 = Team::inRandomOrder()->first();
        $team2 = Team::where('id', '!=', $team1->id)->inRandomOrder()->first();

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

        return [
            'name' => $this->faker->word,
            'event_id' => Event::inRandomOrder()->first()->id,
            'team1_id' => $team1->id,
            'team1_score' => $team1Score,
            'team2_id' => $team2->id,
            'team2_score' => $team2Score,
            'winner' => $winner,
            'losser' => $loser,
            'status' => $status,
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}

