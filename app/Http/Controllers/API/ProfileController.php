<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Profile\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfileController extends ApiController
{
    function profileSeller(): JsonResponse
    {
        $service = ProfileService::profileSeller();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function profileBuyer(): JsonResponse
    {
        $service = ProfileService::profileBuyer();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
