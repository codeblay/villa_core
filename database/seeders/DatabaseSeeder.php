<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(CitySeeder::class);

        if (env('SEED_EXAMPLE', false)) {
            $this->call(SellerSeeder::class);
            $this->call(FacilitySeeder::class);
            $this->call(DestinationSeeder::class);
            $this->call(VillaSeeder::class);
        }
    }
}
