<?php

namespace Database\Seeders;

use App\Models\PerfumeComment;
use Illuminate\Database\Seeder;

class PerfumeCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PerfumeComment::factory(10)->create();
    }
}
