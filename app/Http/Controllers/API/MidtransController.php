<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Midtrans\Callback\MidtransCallbackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransController extends ApiController
{
    function notification(Request $request): JsonResponse
    {
        $service = MidtransCallbackService::notification($request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
