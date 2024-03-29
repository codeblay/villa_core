<?php

namespace App\Services\Midtrans\Callback\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\External\Midtrans;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaScheduleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Notification extends Service
{
    const CONTEXT           = "menerima callback";
    const MESSAGE_SUCCESS   = "berhasil menerima callback";
    const MESSAGE_ERROR     = "gagal menerima callback";

    function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $status         = $this->request->transaction_status;
            $external_id    = $this->request->transaction_id;
            $paid_at        = $this->request->settlement_time;

            $transaction = TransactionRepository::first(['external_id' => $external_id]);
            if (!$transaction) return parent::error(self::MESSAGE_ERROR);

            $transaction_status = $transaction->status;

            switch ($status) {
                case Midtrans::STATUS_SETTLEMENT:
                    if ($transaction_status == Transaction::STATUS_PENDING) {
                        $status_parsed = Transaction::STATUS_SUCCESS;
                    }
                    break;

                case Midtrans::STATUS_PENDING:
                    if (($transaction_status == Transaction::STATUS_NEW)) {
                        $status_parsed = Transaction::STATUS_PENDING;
                    }
                    break;

                case Midtrans::STATUS_CANCEL:
                case Midtrans::STATUS_EXPIRE:
                case Midtrans::STATUS_FAILURE:
                    $status_parsed = Transaction::STATUS_FAILED;
                    VillaScheduleRepository::deleteByTransaction($transaction->id);
                    break;

                default:
                    goto SUCCESS;
            }

            TransactionRepository::update($transaction->id, [
                'status'    => $status_parsed,
                'paid_at'   => $paid_at,
            ]);

            DB::commit();

            SUCCESS:
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_SUCCESS);
        }
    }
}
