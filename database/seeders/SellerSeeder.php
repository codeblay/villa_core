<?php

namespace Database\Seeders;

use App\Models\Seller;
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
            'gender'                => 'Pria',
            'birth_date'            => Carbon::createFromDate(1998, 01, 11),
            'nik'                   => '0000000000000001',
            'is_email_verified'     => true,
            'is_document_verified'  => true,
        ]);
    }
}
