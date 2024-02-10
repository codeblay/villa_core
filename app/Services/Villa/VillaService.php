<?php

namespace App\Services\Villa;

use App\Models\DTO\ServiceResponse;
use App\Services\Villa\Action\Booking;
use App\Services\Villa\Action\Create;
use App\Services\Villa\Action\Detail;
use App\Services\Villa\Action\Slider;

final class VillaService
{
    static function slider(): ServiceResponse
    {
        return (new Slider)->call();
    }
    
    static function detail(int $villa_id): ServiceResponse
    {
        return (new Detail($villa_id))->call();
    }

    static function create(): ServiceResponse
    {
        return (new Create)->call();
    }

    static function booking(): ServiceResponse
    {
        return (new Booking)->call();
    }
}
