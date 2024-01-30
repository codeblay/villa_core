<?php

namespace App\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    function response(bool $status, string $message, array $result, int $http_code): JsonResponse
    {
        $data = [
            'status'    => $status,
            'message'   => $message,
            'result'    => $result,
        ];
        return response()->json($data, $http_code);
    }
}
