<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Services\Midtrans\Transaction\MidtransTransactionService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Accept extends Service
{
    const CONTEXT           = "accept transction";
    const MESSAGE_SUCCESS   = "success accept transction";
    const MESSAGE_ERROR     = "failed accept transction";

    public function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::first(['id' => $this->transaction_id]);
            if (!$transaction || $transaction->status != Transaction::STATUS_PENDING) {
                DB::rollBack();
                return parent::error("data transaksi tidak ditemukan");
            }

            $midtrans = MidtransTransactionService::create($transaction);
            if (!$midtrans) {
                DB::rollBack();
                return parent::error("terjadi kesalahan, cobalah beberapa saat lagi", Response::HTTP_BAD_GATEWAY);
            }

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
