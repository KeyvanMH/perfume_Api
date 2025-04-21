<?php

namespace Database\Seeders;

use App\Models\PerfumeCommentReply;
use Illuminate\Database\Seeder;

class PerfumeCommentReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PerfumeCommentReply::factory(5)->create();
    }
}
