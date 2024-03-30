<?php

namespace App\Services\Auth;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\MyConst;
use App\Services\Auth\Action\CancelForgotPassword;
use App\Services\Auth\Action\DoForgotPassword;
use App\Services\Auth\Action\Login;
use App\Services\Auth\Action\Logout;
use App\Services\Auth\Action\Register;
use App\Services\Auth\Action\ResetPassword;
use App\Services\Auth\Action\ForgotPassword;
use Illuminate\Foundation\Auth\User;
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

    static function forgotPassword(Request $request, string $user_type): ServiceResponse
    {
        return (new ForgotPassword($request, $user_type))->call();
    }

    static function doForgotPassword(Request $request): ServiceResponse
    {
        return (new DoForgotPassword($request))->call();
    }

    static function cancelForgotPassword(Request $request): ServiceResponse
    {
        return (new CancelForgotPassword($request))->call();
    }
}
