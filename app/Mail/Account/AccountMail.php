<?php

namespace App\Mail\Account;

use App\Mail\Account\Action\ForgotPassword;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;

class AccountMail
{
    static function forgotPassword(User $user): ?SentMessage
    {
        return Mail::send(new ForgotPassword($user));
    }
}
