<?php

namespace Database\Seeders;

use App\Models\PerfumeSold;
use App\Models\Sold;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sold::factory(10)->create();
    }
}
