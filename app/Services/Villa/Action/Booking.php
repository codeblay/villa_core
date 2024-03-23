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
            
            $transaction = TransactionRepository::create([
                'code'              => self::generateOrderId(),
                'villa_id'          => $villa->id,
                'buyer_id'          => $this->buyer->id,
                'status'            => Transaction::STATUS_PENDING,
                'amount'            => $villa->price,
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
                    'villa_id'          => $villa->id,
                    'transaction_id'    => $transaction->id,
                    'date'              => $date,
                ]);
            }

            $this->data['order_id'] = $transaction->order_id;

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
    
    private static function generateOrderId() : string {
        $prefix         = "AURA";
        $random_string  = substr(md5(mt_rand()), 0, 9);
        $order_id       = "$prefix-$random_string";

        $check = TransactionRepository::first(['order_id' => $order_id]);
        if ($check) self::generateOrderId();

        return strtoupper("$prefix-$random_string");
    }
}
