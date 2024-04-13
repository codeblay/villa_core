<?php

namespace App\Services\Sendtalk\Callback;

use App\Models\DTO\ServiceResponse;
use App\Services\Sendtalk\Callback\Action\Otp;
use Illuminate\Http\Request;

final class SendtalkCallbackService
{
    static function otp(Request $request): ServiceResponse
    {
        return (new Otp($request))->call();
    }
}
