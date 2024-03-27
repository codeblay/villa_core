<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    function login(Request $request): JsonResponse
    {
        $user_type = $request->header('x-role', '');
        $service = AuthService::login($request, $user_type);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function logout(Request $request): JsonResponse
    {
        $service = AuthService::logout($request);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function register(Request $request): JsonResponse
    {
        $user_type = $request->header('x-role', '');
        $service = AuthService::register($request, $user_type);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function resetPassword(Request $request): JsonResponse
    {
        $service = AuthService::resetPassword($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function forgotPassword(Request $request): JsonResponse
    {
        $user_type = $request->header('x-role', '');
        $service = AuthService::register($request, $user_type);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
