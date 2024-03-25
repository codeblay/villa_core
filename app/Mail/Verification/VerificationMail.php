<?php

namespace App\Mail\Verification;

use App\Mail\Verification\Action\Accept;
use App\Mail\Verification\Action\Deny;
use App\Mail\Verification\Action\Send;
use App\Models\Seller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;

class VerificationMail
{
    static function send(User $user): ?SentMessage
    {
        return Mail::send(new Send($user));
    }
    
    static function accept(Seller $seller): ?SentMessage
    {
        return Mail::send(new Accept($seller));
    }

    static function deny(Seller $seller): ?SentMessage
    {
        return Mail::send(new Deny($seller));
    }
}
