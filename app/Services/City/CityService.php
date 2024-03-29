<?php

namespace App\Services\City;

use App\Models\DTO\ServiceResponse;
use App\Services\City\Action\Dropdown;
use Illuminate\Http\Request;

final class CityService
{
    static function dropdown(Request $request): ServiceResponse
    {
        return (new Dropdown($request))->call();
    }
}
