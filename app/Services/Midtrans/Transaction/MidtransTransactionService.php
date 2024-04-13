<?php

namespace App\Services\Midtrans\Transaction;

use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Services\Midtrans\Transaction\Action\Create;

final class MidtransTransactionService
{
    static function create(Transaction $transaction): ServiceResponse
    {
        return (new Create($transaction))->call();
    }
}
