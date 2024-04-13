<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends ApiController
{

    function dashboard(): JsonResponse
    {
        $service = DashboardService::seller(auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
