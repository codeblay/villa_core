<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends ApiController
{
    function dropdown(Request $request): JsonResponse
    {
        $service = CityService::dropdown($request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
