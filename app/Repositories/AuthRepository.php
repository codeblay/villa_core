<?php

namespace App\Repositories;

use App\Models\Buyer;
use App\Models\Seller;
use Illuminate\Foundation\Auth\User;

final class AuthRepository
{
    static function revokeCurrentToken(User $user) : bool {
        if ($user instanceof Seller || $user instanceof Buyer) {
            $token_id = $user->currentAccessToken()->id;
            return $user->tokens()->where('id', $token_id)->delete();
        }
        
        return false;
    }
}
