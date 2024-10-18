<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Term;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Term>
 */
class TermFactory extends Factory
{
    protected $model = Term::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $startDate = $this->faker->date();
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year')->format('Y-m-d');

        return [
            'name' => $this->faker->word,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}

