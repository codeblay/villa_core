<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\Midtrans\Charge;
use App\Models\DTO\Midtrans\ChargeCustomerDetails;
use App\Models\DTO\Midtrans\ChargeTransactionDetails;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Models\Villa;
use App\Repositories\MidtransRepository;
use App\Repositories\TransactionDetailRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaRepository;
use App\Repositories\VillaScheduleRepository;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

final class Booking extends Service
{
    const CONTEXT           = "booking villa";
    const MESSAGE_SUCCESS   = "success booking villa";
    const MESSAGE_ERROR     = "failed booking villa";

    private static function rules() : array {
        $now = now()->format('Y-m-d');
        return [
            'villa_id'      => ['required', 'integer'],
            'payment'       => ['required', 'string', Rule::in(Charge::PAYMENT)],
            'name'          => ['required', 'string', 'min:2'],
            'start_date'    => ['required', 'date_format:Y-m-d', "after:$now"],
            'end_date'      => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }

    public function __construct(protected Request $request, protected Buyer $buyer)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), self::rules());
            if ($validator->fails()) {
                DB::rollBack();
                return parent::error($validator->errors()->first());
            }
            
            $villa = VillaRepository::first(['id' => $this->request->villa_id]);
            if (!$villa || !$villa->is_publish) {
                DB::rollBack();
                return parent::error("data villa tidak ditemukan");
            }
            $date_booking       = CarbonPeriod::create($this->request->start_date, $this->request->end_date);
            $booked_schedules   = VillaScheduleRepository::get(['villa_id' => $this->request->villa_id])->pluck('date')->toArray();
            foreach ($date_booking as $date) {
                $date_format = $date->format('Y-m-d');
                if (in_array($date_format, $booked_schedules)) {
                    DB::rollBack();
                    return parent::error("villa telah di-booking pada tanggal {$date_format}");
                }
            }

            // midtrans
            $midtrans_charge_transaction_detail                 = new ChargeTransactionDetails;
            $midtrans_charge_transaction_detail->order_id       = self::generateExternalId();
            $midtrans_charge_transaction_detail->gross_amount   = $villa->price;

            $midtrans_charge_customer_detail                = new ChargeCustomerDetails;
            $midtrans_charge_customer_detail->first_name    = $this->buyer->name;
            $midtrans_charge_customer_detail->email         = $this->buyer->email;
            $midtrans_charge_customer_detail->phone         = $this->buyer->phone;

            $midtrans_charge_body                       = new Charge;
            $midtrans_charge_body->payment_type         = $this->request->payment;
            $midtrans_charge_body->transaction_details  = $midtrans_charge_transaction_detail;
            $midtrans_charge_body->customer_details     = $midtrans_charge_customer_detail;
            
            $midtrans_charge = (new MidtransRepository)->charge($midtrans_charge_body);
            if ($midtrans_charge->failed()) {
                DB::rollBack();
                return parent::error("terjadi kesalahan, cobalah beberapa saat lagi", Response::HTTP_BAD_GATEWAY);
            }
            
            // transaction
            $midtrans_charge_result = $midtrans_charge->json();
            $midtrans_charge_result_action = collect($midtrans_charge_result['actions']);
            $midtrans_charge_result_action = [
                'qr'        => $midtrans_charge_result_action->where('name', 'generate-qr-code')->value('url') ?? '',
                'deeplink'  => $midtrans_charge_result_action->where('name', 'deeplink-redirect')->value('url') ?? '',
                'cancel'    => $midtrans_charge_result_action->where('name', 'cancel')->value('url') ?? '',
            ];
            try {       
                $transaction = TransactionRepository::create([
                    'code'              => $midtrans_charge_result['order_id'],
                    'villa_id'          => $villa->id,
                    'buyer_id'          => $this->buyer->id,
                    'status'            => Transaction::STATUS_PENDING,
                    'amount'            => $villa->price,
                    'external_id'       => $midtrans_charge_result['order_id'],
                    'external_response' => $midtrans_charge->body(),
                ]);
    
                TransactionDetailRepository::create([
                    'transaction_id'    => $transaction->id,
                    'name'              => $this->request->name,
                    'start'             => $this->request->start_date,
                    'end'               => $this->request->end_date,
                ]);
    
                // schedule
                foreach ($date_booking as $date) {
                    VillaScheduleRepository::create([
                        'villa_id'  => $villa->id,
                        'date'      => $date,
                    ]);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                (new MidtransRepository)->cancel($midtrans_charge_result_action['cancel']);
                return parent::error("terjadi kesalahan, cobalah beberapa saat lagi");
            }

            $this->data = $midtrans_charge_result_action;

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    static function generateExternalId() : string {
        $prefix         = "villa";
        $random_string  = substr(md5(mt_rand()), 0, 9);
        $external_id    = "$prefix-$random_string";

        $check = TransactionRepository::first(['external_id' => $external_id]);
        if ($check) self::generateExternalId();

        return "$prefix-$random_string";
    }
}
