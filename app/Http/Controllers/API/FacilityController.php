<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Facility\FacilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends ApiController
{
    function dropdown(Request $request): JsonResponse
    {
        $service = FacilityService::dropdown($request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
