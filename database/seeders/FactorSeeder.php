<?php

namespace Database\Seeders;

use App\Models\Factor;
use Illuminate\Database\Seeder;

class FactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Factor::factory(10)->create();
    }
}
