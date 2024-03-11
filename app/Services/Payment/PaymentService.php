<?php

namespace App\Services\Payment;

use App\Models\DTO\ServiceResponse;
use App\Services\Payment\Action\Get;

final class PaymentService
{
    static function Get(): ServiceResponse
    {
        return (new Get)->call();
    }
}
