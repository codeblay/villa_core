<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends ApiController
{
    function list(): JsonResponse
    {
        $service = PaymentService::Get();

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
