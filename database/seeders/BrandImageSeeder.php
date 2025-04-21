<?php

namespace Database\Seeders;

use App\Models\BrandImage;
use Illuminate\Database\Seeder;

class BrandImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BrandImage::factory(10)->create();
    }
}
