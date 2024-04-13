<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\MidtransRepository;
use App\Repositories\TransactionRepository;
use App\Services\Midtrans\Callback\MidtransCallbackService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Sync extends Service
{
    const CONTEXT           = "sinkron transaksi";
    const MESSAGE_SUCCESS   = "berhasil sinkron transaksi";
    const MESSAGE_ERROR     = "gagal sinkron transaksi";

    function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            if (!$transaction) return parent::error('transaction not found', Response::HTTP_BAD_REQUEST);

            $midtrans = (new MidtransRepository)->status($transaction->code);
            if ($midtrans->failed()) parent::error(self::MESSAGE_SUCCESS, Response::HTTP_BAD_GATEWAY);

            $midtrans_result = $midtrans->json();

            $midtrans = MidtransCallbackService::notification(new Request($midtrans_result));
            if (!$midtrans->status) return parent::error("terjadi kesalahan, cobalah beberapa saat lagi", Response::HTTP_BAD_GATEWAY);

            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            $this->data = Detail::mapResult($transaction);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
