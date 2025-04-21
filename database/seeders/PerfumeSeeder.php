<?php

namespace Database\Seeders;

use App\Models\Perfume;
use Illuminate\Database\Seeder;

class PerfumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perfume::factory(20)->create();
    }
}
