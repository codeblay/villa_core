<?php

namespace App\Services\Verification;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\MyConst;
use App\Services\Auth\Verification\Accept;
use App\Services\Auth\Verification\Deny;
use App\Services\Auth\Verification\Email;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class VerificationService
{
    static function email(Request $request): ServiceResponse
    {
        return (new Email($request))->call();
    }

    static function accept(int $seller_id): ServiceResponse
    {
        return (new Accept($seller_id))->call();
    }

    static function deny(int $seller_id): ServiceResponse
    {
        return (new Deny($seller_id))->call();
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
