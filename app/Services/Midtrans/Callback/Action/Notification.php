<?php

namespace App\Services\Midtrans\Callback\Action;

use App\Base\Service;
use App\Mail\Booking\BookingMail;
use App\Models\DTO\ServiceResponse;
use App\Models\External\Midtrans;
use App\Models\Mutation;
use App\Models\Transaction;
use App\Repositories\FirebaseRepository;
use App\Repositories\MutationRepository;
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
            if (!$this->verifySignature($this->request)) return parent::error(self::MESSAGE_ERROR);

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
                        MutationRepository::create([
                            'seller_id' => $transaction->villa->seller->id,
                            'amount'    => $transaction->amount,
                            'type'      => Mutation::TYPE_RENT,
                        ]);

                        BookingMail::ticket($transaction);

                        if ($transaction->villa->seller->fcm_token) {
                            (new FirebaseRepository)->send($transaction->villa->seller->fcm_token, "Pembayaran Diterima", "Pembayaran diterima dengan kode booking {$transaction->code}");
                        }

                        if ($transaction->buyer->fcm_token) {
                            (new FirebaseRepository)->send($transaction->buyer->fcm_token, "Pembayaran Berhasil", "Pembayaran berhasil dengan kode booking {$transaction->code}");
                        }
                    }
                    break;

                case Midtrans::STATUS_PENDING:
                    if (($transaction_status == Transaction::STATUS_NEW)) {
                        $status_parsed = Transaction::STATUS_PENDING;
                    }
                    break;

                case Midtrans::STATUS_CANCEL:
                    $status_parsed = Transaction::STATUS_CANCEL;
                    break;

                case Midtrans::STATUS_EXPIRE:
                case Midtrans::STATUS_FAILURE:
                    $status_parsed = Transaction::STATUS_FAILED;
                    VillaScheduleRepository::deleteByTransaction($transaction->id);
                    if ($transaction->buyer->fcm_token) {
                        (new FirebaseRepository)->send($transaction->buyer->fcm_token, "Pembayaran Gagal", "Pembayaran gagal dengan kode booking {$transaction->code}");
                    }
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
            return parent::success(self::MESSAGE_SUCCESS);
        }
    }

    private function verifySignature(): bool
    {
        $status_code    = $this->request->status_code;
        $gross_amount   = $this->request->gross_amount;
        $order_id       = $this->request->order_id;
        $signature_key  = $this->request->signature_key;

        $server_key = config('midtrans.server_key');

        return $signature_key == hash("sha512", "$order_id$status_code$gross_amount$server_key");
    }
}
