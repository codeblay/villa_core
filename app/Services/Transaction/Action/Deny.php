<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\FirebaseRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaScheduleRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Deny extends Service
{
    const CONTEXT           = "menolak transaksi";
    const MESSAGE_SUCCESS   = "berhasil menolak transaksi";
    const MESSAGE_ERROR     = "gagal menolak transaksi";

    public function __construct(protected int $transaction_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $transaction = TransactionRepository::first(['id' => $this->transaction_id]);
            if (!$transaction) {
                DB::rollBack();
                return parent::error("transaksi tidak ditemukan");
            }

            if (!$transaction->can_deny) {
                DB::rollBack();
                return parent::error("transaksi tidak dapat ditolak");
            }

            TransactionRepository::update($transaction->id, ['status' => Transaction::STATUS_REJECT]);
            VillaScheduleRepository::deleteByTransaction($transaction->id);

            if ($transaction->buyer->fcm_token) {
                (new FirebaseRepository)->send($transaction->buyer->fcm_token, "Booking Gagal", "Booking gagal dengan kode booking {$transaction->code}");
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
