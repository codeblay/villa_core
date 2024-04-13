<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Banner\BannerService;
use Illuminate\Http\JsonResponse;

class BannerController extends ApiController
{
    function get(): JsonResponse
    {
        $service = BannerService::Get();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
