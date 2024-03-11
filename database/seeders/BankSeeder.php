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
                'name'      => 'QR',
                'code'      => 'qr',
                'is_active' => true,
            ],
            [
                'name' => 'BCA',
                'code' => 'bca',
            ],
            [
                'name' => 'BRI',
                'code' => 'bri',
            ],
            [
                'name' => 'BNI',
                'code' => 'bni',
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
