<?php

namespace App\Services\Midtrans\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\Midtrans\Charge;
use App\Models\DTO\Midtrans\ChargeBankTransfer;
use App\Models\DTO\Midtrans\ChargeCustomerDetails;
use App\Models\DTO\Midtrans\ChargeEchannel;
use App\Models\DTO\Midtrans\ChargeItemDetails;
use App\Models\DTO\Midtrans\ChargeTransactionDetails;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\MidtransRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Response;

final class Create extends Service
{
    const CONTEXT           = "menyimpan transaksi midtrans";
    const MESSAGE_SUCCESS   = "berhasil menyimpan transaksi midtrans";
    const MESSAGE_ERROR     = "gagal menyimpan transaksi midtrans";

    function __construct(protected Transaction $transaction)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $payment_type = $this->transaction->bank->midtrans_payment_type;

            $midtrans_charge_transaction_detail                 = new ChargeTransactionDetails;
            $midtrans_charge_transaction_detail->order_id       = $this->transaction->code;
            $midtrans_charge_transaction_detail->gross_amount   = $this->transaction->amount + $this->transaction->fee;

            $midtrans_charge_customer_detail                = new ChargeCustomerDetails;
            $midtrans_charge_customer_detail->first_name    = $this->transaction->buyer->name;
            $midtrans_charge_customer_detail->email         = $this->transaction->buyer->email;
            $midtrans_charge_customer_detail->phone         = $this->transaction->buyer->phone;

            $midtrans_charge_body                       = new Charge;
            $midtrans_charge_body->payment_type         = $payment_type;
            $midtrans_charge_body->transaction_details  = $midtrans_charge_transaction_detail;
            $midtrans_charge_body->customer_details     = $midtrans_charge_customer_detail;

            $midtrans_charge_item_detail            = new ChargeItemDetails;
            $midtrans_charge_item_detail->name      = $this->transaction->transactionDetail->villa_name;
            $midtrans_charge_item_detail->price     = $this->transaction->transactionDetail->villa_price;
            $midtrans_charge_item_detail->quantity  = $this->transaction->amount / ($this->transaction->transactionDetail->villa_price);
            $midtrans_charge_body->item_details[]   = $midtrans_charge_item_detail;

            $midtrans_charge_item_detail            = new ChargeItemDetails;
            $midtrans_charge_item_detail->name      = "Biaya Layanan";
            $midtrans_charge_item_detail->price     = $this->transaction->fee;
            $midtrans_charge_item_detail->quantity  = 1;
            $midtrans_charge_body->item_details[]   = $midtrans_charge_item_detail;

            switch ($payment_type) {
                case Charge::PAYMENT_TYPE_BANK_TRANSFER:
                    $midtrans_charge_bank_transfer              = new ChargeBankTransfer;
                    $midtrans_charge_bank_transfer->bank        = $this->transaction->bank->code;
                    $midtrans_charge_body->bank_transfer        = $midtrans_charge_bank_transfer;
                    break;
                case Charge::PAYMENT_TYPE_ECHANNEL:
                    $midtrans_charge_echannel               = new ChargeEchannel;
                    $midtrans_charge_echannel->bill_info1   = "Pembayaran:";
                    $midtrans_charge_echannel->bill_info2   = "Raja Villa";
                    $midtrans_charge_body->echannel         = $midtrans_charge_echannel;

                    break;
            }

            $midtrans_charge = (new MidtransRepository)->charge($midtrans_charge_body);
            if ($midtrans_charge->failed()) parent::error(self::MESSAGE_SUCCESS, Response::HTTP_BAD_GATEWAY);

            $midtrans_charge_result = $midtrans_charge->json();

            try {
                TransactionRepository::update($this->transaction->id, [
                    'external_id'       => $midtrans_charge_result['transaction_id'],
                    'external_response' => $midtrans_charge->body(),
                ]);
            } catch (\Throwable $th) {
                if ($payment_type == Charge::PAYMENT_TYPE_QRIS) {
                    (new MidtransRepository)->cancel($this->transaction->code);
                }
            }

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR);
        }
    }
}
