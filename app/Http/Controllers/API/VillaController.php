<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\API\Villa\VillaService;
use App\Services\Villa\VillaService as VillaService2;
use Illuminate\Http\JsonResponse;

class VillaController extends ApiController
{

    function index(): JsonResponse
    {
        $service = VillaService::ListBySeller(auth()->user()->id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function detail(int $villa_id): JsonResponse
    {
        $service = VillaService2::detail($villa_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function slider(): JsonResponse
    {
        $service = VillaService2::slider();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
