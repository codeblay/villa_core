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

    function listBySeller(Request $request): JsonResponse
    {
        $service = VillaService::ListBySeller($request, auth()->user());

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

    function detailUnit(int $villa_id, int $unit_id): JsonResponse
    {
        $service = VillaService::unitDetailBuyer($villa_id, $unit_id);

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

    function check(int $villa_type_id): JsonResponse
    {
        $service = VillaService::check($villa_type_id, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function unit(int $villa_id): JsonResponse
    {
        $service = VillaService::unit($villa_id, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function unitDetail(int $villa_id, int $unit_id): JsonResponse
    {
        $service = VillaService::unitDetail($villa_id, $unit_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
