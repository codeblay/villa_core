<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\MyConst;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seller::query()->create([
            'name'                  => 'Seller Dummy',
            'email'                 => 'seller@gmail.com',
            'password'              => 'qweqweqwe',
            'phone'                 => '080000000001',
            'gender'                => MyConst::GENDER_MALE,
            'birth_date'            => Carbon::createFromDate(1998, 01, 11),
            'nik'                   => '0000000000000001',
            'email_verified_at'     => now(),
            'document_verified_at'  => now(),
        ]);
    }
}
