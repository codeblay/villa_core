<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Response;

final class Detail extends Service
{
    const CONTEXT           = "memuat transaksi";
    const MESSAGE_SUCCESS   = "berhasil memuat transaksi";
    const MESSAGE_ERROR     = "gagal memuat transaksi";

    function __construct(protected int $transaction_id, protected Seller|Buyer $user)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $conditions['id'] = $this->transaction_id;
            if ($this->user instanceof Buyer) $conditions['buyer_id'] = $this->user->id;

            $transaction = TransactionRepository::first($conditions);
            if (!$transaction) return parent::error('transaksi tidak ditemukan', Response::HTTP_BAD_REQUEST);

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
            'can_rate'  => $transaction->status == Transaction::STATUS_SUCCESS && is_null($transaction->villaRating),
            'rating'    => $transaction->villaRating->rating ?? 0,
            'bank' => [
                'id'        => $transaction->bank->id,
                'code'      => $transaction->bank->code,
                'name'      => $transaction->bank->name,
            ],
            'villa' => [
                'id'        => $transaction->villa->id,
                'name'      => $transaction->villa->name,
                'address'   => $transaction->villa->city->address,
                'image'     => $transaction->villa->file->local_path,
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
