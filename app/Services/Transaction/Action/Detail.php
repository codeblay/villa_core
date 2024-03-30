<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Detail extends Service
{
    const CONTEXT           = "load transaction";
    const MESSAGE_SUCCESS   = "berhasil load transaction";
    const MESSAGE_ERROR     = "gagal load transaction";

    function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            if (!$transaction) return parent::error('transaction not found', Response::HTTP_BAD_REQUEST);

            $this->data = self::mapResult($transaction);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    static function mapResult(Transaction $transaction) : array {
        return [
            'id'        => $transaction->id,
            'code'      => $transaction->code,
            'amount'    => $transaction->amount,
            'fee'       => $transaction->fee,
            'status'    => $transaction->status_label,
            'bank' => [
                'id'        => $transaction->bank->id,
                'code'      => $transaction->bank->code,
                'name'      => $transaction->bank->name,
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
    }
}
