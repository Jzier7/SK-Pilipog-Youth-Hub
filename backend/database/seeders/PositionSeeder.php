<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the SK Chairman position
        Position::factory()->create([
            'name' => 'SK Chairman',
            'level' => 1,
        ]);

        // Create the Kagawad position
        Position::factory()->create([
            'name' => 'SK Kagawad',
            'level' => 2,
        ]);

        // Optionally create random positions
        // Position::factory()->count(10)->create();
    }
}

