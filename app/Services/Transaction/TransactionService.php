<?php

namespace App\Services\Transaction;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Services\Transaction\Action\Accept;
use App\Services\Transaction\Action\Deny;
use App\Services\Transaction\Action\Detail;
use App\Services\Transaction\Action\ListForBuyer;
use App\Services\Transaction\Action\ListForSeller;
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

    static function accept(int $transaction_id): ServiceResponse
    {
        return (new Accept($transaction_id))->call();
    }

    static function deny(int $transaction_id): ServiceResponse
    {
        return (new Deny($transaction_id))->call();
    }

    static function ListForSeller(Request $request, Seller $seller): ServiceResponse
    {
        return (new ListForSeller($request, $seller))->call();
    }
}
