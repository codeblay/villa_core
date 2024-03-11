<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    const FACILITIES = [
        'Wifi',
        'Kolam Renang',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(self::FACILITIES as $facility) {
            Facility::query()->create(['name' => $facility]);
        }
    }
}
