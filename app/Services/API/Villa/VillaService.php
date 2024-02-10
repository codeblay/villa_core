<?php

namespace App\Services\API\Villa;

use App\Models\DTO\ServiceResponse;
use App\Services\API\Villa\Action\ListBySeller;

final class VillaService
{
    static function ListBySeller(int $seller_id): ServiceResponse
    {
        return (new ListBySeller($seller_id))->call();
    }
}
