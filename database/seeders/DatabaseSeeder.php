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
//        User::factory(10)->create();
        // Call the FaqSeeder
        $this->call(FaqSeeder::class);

        // Call the ContactSeeder
        $this->call(ContactUsSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(BrandImageSeeder::class);

    }
}
