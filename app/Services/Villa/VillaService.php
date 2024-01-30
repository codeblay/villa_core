<?php

namespace App\Services\Villa;

use App\Models\DTO\ServiceResponse;
use App\Services\Villa\Action\Booking;
use App\Services\Villa\Action\Create;

final class VillaService
{
    static function create(): ServiceResponse
    {
        return (new Create)->call();
    }

    static function booking(): ServiceResponse
    {
        return (new Booking)->call();
    }
}
