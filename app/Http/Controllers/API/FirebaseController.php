<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Firebase\FirebaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FirebaseController extends ApiController
{
    function send(Request $request): JsonResponse
    {
        $service = FirebaseService::send(auth()->user(), $request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
