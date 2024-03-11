<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Sendtalk\Callback\SendtalkCallbackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendtalkController extends ApiController
{
    function otp(Request $request): JsonResponse
    {
        $service = SendtalkCallbackService::otp($request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
