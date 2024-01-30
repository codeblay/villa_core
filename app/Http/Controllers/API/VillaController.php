<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Villa\VillaService;
use Illuminate\Http\JsonResponse;

class VillaController extends ApiController
{

    function create(): JsonResponse
    {
        $service = VillaService::create();

        return parent::response(
            status: $service->status,
            message: $service->message,
            result: $service->result,
            http_code: $service->code,
        );
    }

    function booking(): JsonResponse
    {
        $service = VillaService::booking();

        return parent::response(
            status: $service->status,
            message: $service->message,
            result: $service->result,
            http_code: $service->code,
        );
    }
}
