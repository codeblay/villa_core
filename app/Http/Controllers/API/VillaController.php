<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Villa\VillaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillaController extends ApiController
{

    function list(Request $request): JsonResponse
    {
        $service = VillaService::ListAll($request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function listBySeller(): JsonResponse
    {
        $service = VillaService::ListBySeller(auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function detail(int $villa_id): JsonResponse
    {
        $service = VillaService::detail($villa_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function slider(): JsonResponse
    {
        $service = VillaService::slider();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function create(Request $request): JsonResponse
    {
        $service = VillaService::create($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
