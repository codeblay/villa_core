<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaScheduleRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Deny extends Service
{
    const CONTEXT           = "deny transaction";
    const MESSAGE_SUCCESS   = "success deny transaction";
    const MESSAGE_ERROR     = "failed deny transaction";

    public function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            if (!$transaction || !$transaction->can_deny) {
                DB::rollBack();
                return parent::error("data transaksi tidak ditemukan");
            }

            TransactionRepository::update($transaction->id, ['status' => Transaction::STATUS_FAILED]);
            VillaScheduleRepository::deleteByTransaction($transaction->id);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
