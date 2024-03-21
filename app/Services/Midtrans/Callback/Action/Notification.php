<?php

namespace App\Services\Midtrans\Callback\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\External\Midtrans;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Notification extends Service
{
    const CONTEXT           = "receive callback";
    const MESSAGE_SUCCESS   = "success receive callback";
    const MESSAGE_ERROR     = "failed receive callback";

    function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $status         = $this->request->transaction_status;
            $external_id    = $this->request->transaction_id;
            $paid_at        = $this->request->settlement_time;
    
            $transaction = TransactionRepository::first(['external_id' => $external_id]);
            if (!$transaction) return parent::error(self::MESSAGE_ERROR);

            $update = [
                'paid_at' => $paid_at
            ];

            switch ($status) {
                case Midtrans::STATUS_SETTLEMENT:
                    $update['status'] = Transaction::STATUS_SUCCESS;
                    break;
                case Midtrans::STATUS_CANCEL:
                case Midtrans::STATUS_EXPIRE:
                case Midtrans::STATUS_FAILUER:
                   $update['status'] = Transaction::STATUS_FAILED;
                    break;
            }
    
            TransactionRepository::update($transaction->id, $update);
    
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return parent::error(self::MESSAGE_SUCCESS);
        }
    }
}
