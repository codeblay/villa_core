<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\FirebaseRepository;
use App\Repositories\TransactionRepository;
use App\Services\Midtrans\Transaction\MidtransTransactionService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Accept extends Service
{
    const CONTEXT           = "menerima transaksi";
    const MESSAGE_SUCCESS   = "berhasil menerima transaksi";
    const MESSAGE_ERROR     = "gagal menerima transaksi";

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
                return parent::error("data transaksi tidak ditemukan");
            }

            if (!$transaction->can_accept) {
                DB::rollBack();
                return parent::error("transaksi tidak dapat diproses");
            }

            $midtrans = MidtransTransactionService::create($transaction);
            if (!$midtrans->status) {
                DB::rollBack();
                return parent::error("terjadi kesalahan, cobalah beberapa saat lagi", Response::HTTP_BAD_GATEWAY);
            }

            if ($transaction->buyer->fcm_token) {
                (new FirebaseRepository)->send($transaction->buyer->fcm_token, "Booking Berhasil", "Booking diterima dengan kode booking {$transaction->code}");
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
