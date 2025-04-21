<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call(FaqSeeder::class);

        $this->call(ContactUsSeeder::class);

        $this->call(BrandSeeder::class);
        $this->call(BrandImageSeeder::class);

        $this->call(CategorySeeder::class);
        $this->call(PerfumeSeeder::class);
        $this->call(PerfumeImageSeeder::class);
        $this->call(PerfumeCommentSeeder::class);
        $this->call(PerfumeCommentReplySeeder::class);
        //        $this->call(DiscountSeeder::class);

        $this->call(FactorSeeder::class);
        $this->call(PerfumeBasedFactorSeeder::class);
        //        $this->call(WarrantySeeder::class);
        //        $this->call(SoldSeeder::class);
    }
}
