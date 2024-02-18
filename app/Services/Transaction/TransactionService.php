<?php

namespace App\Services\Transaction;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Services\Transaction\Action\Detail;
use App\Services\Transaction\Action\ListForBuyer;
use Illuminate\Http\Request;

final class TransactionService
{
    static function ListForBuyer(Request $request, Buyer $buyer): ServiceResponse
    {
        return (new ListForBuyer($request, $buyer))->call();
    }

    static function detail(int $transaction_id): ServiceResponse
    {
        return (new Detail($transaction_id))->call();
    }
}
