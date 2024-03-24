<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Detail extends Service
{
    const CONTEXT           = "load transaction";
    const MESSAGE_SUCCESS   = "success load transaction";
    const MESSAGE_ERROR     = "failed load transaction";

    function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            if (!$transaction) return parent::error('transaction not found', Response::HTTP_BAD_REQUEST);

            $this->data = [
                'id'        => $transaction->id,
                'code'      => $transaction->code,
                'status'    => $transaction->status,
                'amount'    => $transaction->amount,
                'bank' => [
                    'id'        => $transaction->id,
                    'code'      => $transaction->code,
                    'name'      => $transaction->name,
                    'va_number' => $transaction->va_number,
                ],
                'villa' => [
                    'id'        => $transaction->villa->id,
                    'name'      => $transaction->villa->name,
                    'address'   => $transaction->villa->city->address,
                ],
                'detail' => [
                    'name'  => $transaction->transactionDetail->name,
                    'start' => $transaction->transactionDetail->start,
                    'end'   => $transaction->transactionDetail->end,
                ],
                'response'  => $transaction->external_response_parse,
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
