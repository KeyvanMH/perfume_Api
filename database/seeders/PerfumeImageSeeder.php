<?php

namespace Database\Seeders;

use App\Models\PerfumeImage;
use Illuminate\Database\Seeder;

class PerfumeImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PerfumeImage::factory(5)->create();
    }
}
