<?php

namespace Database\Seeders;

use App\Models\Villa;
use App\Models\VillaFacility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $villa = Villa::query()->create([
                'name'          => 'Viila Merapi',
                'seller_id'     => 1,
                'city_id'       => 226,
                'description'   => 'description villa',
                'price'         => 500_000,
                'is_publish'    => true,
                'is_available'  => true,
            ]);

            VillaFacility::query()->create([
                'villa_id'      => $villa->id,
                'facility_id'   => 1,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
