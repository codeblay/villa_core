<?php

namespace App\Services\Dashboard;

use App\Models\DTO\ServiceResponse;
use App\Models\Seller as ModelsSeller;
use App\Services\Dashboard\Action\Admin;
use App\Services\Dashboard\Action\Seller;

final class DashboardService
{
    static function seller(ModelsSeller $seller): ServiceResponse
    {
        return (new Seller($seller))->call();
    }
    
    static function admin(): ServiceResponse
    {
        return (new Admin)->call();
    }
}
