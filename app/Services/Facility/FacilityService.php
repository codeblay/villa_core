<?php

namespace App\Services\Facility;

use App\Models\DTO\ServiceResponse;
use App\Services\Facility\Action\Create;
use App\Services\Facility\Action\Dropdown;
use Illuminate\Http\Request;

final class FacilityService
{
    static function create(Request $request): ServiceResponse
    {
        return (new Create($request))->call();
    }

    static function dropdown(): ServiceResponse
    {
        return (new Dropdown)->call();
    }
}
