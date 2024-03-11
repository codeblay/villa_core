<?php

namespace App\Services\Facility;

use App\Models\DTO\ServiceResponse;
use App\Services\Facility\Action\Create;
use Illuminate\Http\Request;

final class FacilityService
{
    static function create(Request $request): ServiceResponse
    {
        return (new Create($request))->call();
    }
}
