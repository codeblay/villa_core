<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Facility\FacilityService;
use Illuminate\Http\JsonResponse;

class FacilityController extends ApiController
{
    function dropdown(): JsonResponse
    {
        $service = FacilityService::dropdown();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
