<?php

namespace Database\Seeders;

use App\Models\PerfumeBasedFactor;
use Illuminate\Database\Seeder;

class PerfumeBasedFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PerfumeBasedFactor::factory(10)->create();
    }
}
