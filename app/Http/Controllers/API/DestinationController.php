<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Destination\DestinationService;
use Illuminate\Http\JsonResponse;

class DestinationController extends ApiController
{
    function detail(int $destination_id): JsonResponse
    {
        $service = DestinationService::detail($destination_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function listByCategory(int $category_id): JsonResponse
    {
        $service = DestinationService::listByCategory($category_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
