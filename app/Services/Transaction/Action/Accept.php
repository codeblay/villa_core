<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\TransactionRepository;
use App\Services\Midtrans\Transaction\MidtransTransactionService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Accept extends Service
{
    const CONTEXT           = "accept transaction";
    const MESSAGE_SUCCESS   = "success accept transaction";
    const MESSAGE_ERROR     = "failed accept transaction";

    public function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            if (!$transaction || !$transaction->can_accept) {
                DB::rollBack();
                return parent::error("data transaksi tidak ditemukan");
            }

            $midtrans = MidtransTransactionService::create($transaction);
            if (!$midtrans->status) {
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
