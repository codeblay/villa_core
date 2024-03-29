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

    function edit(Request $request, int $id): JsonResponse
    {
        $request->merge(['id' => $id]);
        $service = VillaService::edit($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function rate(Request $request): JsonResponse
    {
        $service = VillaService::rate($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function booking(Request $request): JsonResponse
    {
        $service = VillaService::booking($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function check(int $villa_id): JsonResponse
    {
        $service = VillaService::check($villa_id, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
