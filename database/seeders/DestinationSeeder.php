<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinationSeeder extends Seeder
{
    const DATA = [
        'Wisata' => [
            [
                'name'                      => 'Pantai Tanjung Kesirat',
                'city_id'                   => 225,
                'destination_category_id'   => 1,
                'description'               => 'description destination',
            ],
            [
                'name'                      => 'Merapi Park',
                'city_id'                   => 226,
                'destination_category_id'   => 1,
                'description'               => 'description destination',
            ]
        ],
        'Kuliner' => [
            [
                'name'                      => 'Sate Klathak',
                'city_id'                   => 224,
                'destination_category_id'   => 2,
                'description'               => 'description destination',
            ],
            [
                'name'                      => 'Pawon Tembi',
                'city_id'                   => 226,
                'destination_category_id'   => 2,
                'description'               => 'description destination',
            ]
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            foreach (self::DATA as $category => $destinations) {
                DestinationCategory::query()->create(['name' => $category]);
                foreach ($destinations as $destination) {
                    Destination::query()->create($destination);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
