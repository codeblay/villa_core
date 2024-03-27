<?php

namespace App\Services\Auth;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Services\Auth\Action\Login;
use App\Services\Auth\Action\Logout;
use App\Services\Auth\Action\Register;
use App\Services\Auth\Action\ResetPassword;
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

    static function resetPassword(Request $request, Seller|Buyer $user): ServiceResponse
    {
        return (new ResetPassword($request, $user))->call();
    }
}
