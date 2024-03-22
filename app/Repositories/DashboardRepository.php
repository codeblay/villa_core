<?php

namespace App\Repositories;

use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Villa;

final class DashboardRepository
{
    static function dashboard(): array
    {
        $data = [
            'villa'         => Villa::count(),
            'seller'        => Seller::count(),
            'buyer'         => Buyer::count(),
            'transaction'   => Transaction::where('status', Transaction::STATUS_SUCCESS)->count(),
        ];

        return $data;
    }
}
