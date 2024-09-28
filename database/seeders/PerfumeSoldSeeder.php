<?php

namespace Database\Seeders;

use App\Models\PerfumeSold;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerfumeSoldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PerfumeSold::factory(10)->create();
    }
}
