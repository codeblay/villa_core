<?php

namespace App\Services\Midtrans\Callback;

use App\Models\DTO\ServiceResponse;
use App\Services\Midtrans\Callback\Action\Notification;
use Illuminate\Http\Request;

final class MidtransCallbackService
{
    static function notification(Request $request): ServiceResponse
    {
        return (new Notification($request))->call();
    }
}
