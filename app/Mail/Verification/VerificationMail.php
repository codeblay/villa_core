<?php

namespace App\Mail\Verifiaction;

use App\Mail\Verifiaction\Action\Accept;
use App\Mail\Verifiaction\Action\Deny;
use App\Mail\Verifiaction\Action\Send;
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
