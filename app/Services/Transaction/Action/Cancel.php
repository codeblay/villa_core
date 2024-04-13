<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\FirebaseRepository;
use App\Repositories\MidtransRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaScheduleRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Cancel extends Service
{
    const CONTEXT           = "membatalkan transaksi";
    const MESSAGE_SUCCESS   = "berhasil membatalkan transaksi";
    const MESSAGE_ERROR     = "gagal membatalkan transaksi";

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
            
            if (!$transaction->can_cancel) {
                DB::rollBack();
                return parent::error("transaksi tidak dapat dibatalkan");
            }

            if ($transaction->status == Transaction::STATUS_PENDING) {
                $cancel = (new MidtransRepository)->cancel($transaction->code);
                if ($cancel->failed()) return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_GATEWAY);
            }

            TransactionRepository::update($transaction->id, ['status' => Transaction::STATUS_CANCEL]);
            VillaScheduleRepository::deleteByTransaction($transaction->id);

            if ($transaction->villa->seller->fcm_token) {
                (new FirebaseRepository)->send($transaction->villa->seller->fcm_token, "Booking Dibatalkan", "Booking dibatalkan dengan kode booking {$transaction->code}");
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
