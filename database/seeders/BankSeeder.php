<?php

namespace Database\Seeders;

use App\Repositories\BankRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'name'      => 'QRIS',
                'code'      => 'qr',
                'fee'       => 5_000,
                'is_active' => true,
            ],
            [
                'name'  => 'BRI',
                'code'  => 'bri',
                'fee'   => 5_000,
            ],
            [
                'name'  => 'BNI',
                'code'  => 'bni',
                'fee'   => 5_000,
            ],
            [
                'name'  => 'Mandiri',
                'code'  => 'mandiri',
                'fee'   => 5_000,
            ],
            [
                'name'  => 'CIMB',
                'code'  => 'cimb',
                'fee'   => 5_000,
            ],
            [
                'name'  => 'Permata',
                'code'  => 'permata',
                'fee'   => 5_000,
            ]
        ];

        DB::beginTransaction();
        try {
            foreach ($banks as $bank) {
                BankRepository::create($bank);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
