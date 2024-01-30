<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $cities = json_decode(file_get_contents(public_path('json/cities.json')));
    
            foreach ($cities as $city) {
                City::query()->create([
                    'name'          => $city->name,
                    'province_id'   => $city->province_id,
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
