<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        $teamNames = [
            'Wildcats',
            'Sharks',
            'Falcons',
            'Dragons',
            'Tigers',
            'Eagles',
            'Panthers',
            'Warriors',
            'Knights',
            'Bulldogs',
            'Rangers',
            'Titans',
            'Lions',
            'Comets',
            'Thunderbolts',
            'Vipers',
            'Mustangs',
            'Hawks',
            'Scorpions',
            'Bears',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($teamNames),
            'event_id' => Event::all()->random()->id,
        ];
    }
}

