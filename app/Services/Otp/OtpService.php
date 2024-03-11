<?php

namespace App\Services\Otp;

use App\Models\DTO\ServiceResponse;
use App\Services\Otp\Action\Send;

class OtpService
{

    static function send(int $phone): ServiceResponse
    {
        return (new Send($phone))->call();
    }}
