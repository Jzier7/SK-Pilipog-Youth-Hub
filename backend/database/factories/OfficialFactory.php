<?php

namespace Database\Factories;

use App\Models\Official;
use App\Models\Term;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficialFactory extends Factory
{
    protected $model = Official::class;

    private static $termCounts = [];

    public function definition(): array
    {
        $term = Term::inRandomOrder()->first();

        if (!isset(self::$termCounts[$term->id])) {
            self::$termCounts[$term->id] = 0;
        }

        self::$termCounts[$term->id]++;

        $position = self::$termCounts[$term->id] === 1
            ? Position::where('name', 'SK Chairman')->first()
            : Position::where('name', 'SK Kagawad')->first();

        return [
            'name' => $this->faker->name,
            'position_id' => $position->id,
            'term_id' => $term->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

