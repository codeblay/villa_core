<?php

namespace App\Services\API\Auth;

use App\Models\DTO\ServiceResponse;
use App\Services\API\Auth\Action\Login;
use App\Services\API\Auth\Action\Logout;
use App\Services\API\Auth\Action\Register;
use Illuminate\Http\Request;

class AuthService
{

    static function login(Request $request, string $user_type): ServiceResponse
    {
        return (new Login($request, $user_type))->call();
    }

    static function logout(Request $request): ServiceResponse
    {
        return (new Logout($request))->call();
    }

    static function register(Request $request, string $user_type): ServiceResponse
    {
        return (new Register($request, $user_type))->call();
    }
}
