<?php

namespace App\Services\Firebase;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Services\Firebase\Action\Send;
use Illuminate\Http\Request;

final class FirebaseService
{
    static function send(Seller|Buyer $user, Request $request): ServiceResponse
    {
        return (new Send($user, $request))->call();
    }
}
