<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purok;

class PurokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 Purok records
        Purok::factory()->count(10)->create();
    }
}

