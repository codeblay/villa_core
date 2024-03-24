<?php

namespace App\Services\Auth;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\MyConst;
use App\Services\Auth\Action\Login;
use App\Services\Auth\Action\Logout;
use App\Services\Auth\Action\Register;
use App\Services\Auth\Action\Verification;
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

    static function verification(Request $request): ServiceResponse
    {
        return (new Verification($request))->call();
    }    

    static function generateTokenVerification(User $user) {
        if ($user instanceof Seller) $type = MyConst::USER_SELLER;
        if ($user instanceof Buyer) $type = MyConst::USER_BUYER;

        $email      = $user->email;
        $created    = $user->created_at->timestamp;
        
        $b = base64_encode("$type||$email||$created");
        $e = encrypt($b);

        return $e;
    }
}
