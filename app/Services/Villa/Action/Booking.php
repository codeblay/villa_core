<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Bank;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\BankRepository;
use App\Repositories\FirebaseRepository;
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
    const MESSAGE_SUCCESS   = "berhasil booking villa";
    const MESSAGE_ERROR     = "gagal booking villa";

    private static function rules() : array {
        $now = now()->format('Y-m-d');
        return [
            'villa_id'      => ['required', 'integer'],
            'payment'       => ['required', 'string', Rule::in(Bank::BANK_CODE)],
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

            $bank = BankRepository::first(['code' => $this->request->payment]);

            $transaction = TransactionRepository::create([
                'code'              => self::generateCode(),
                'villa_id'          => $villa->id,
                'buyer_id'          => $this->buyer->id,
                'bank_id'           => $bank->id,
                'status'            => Transaction::STATUS_NEW,
                'amount'            => $villa->price * count($date_booking),
                'fee'               => $bank->fee,
            ]);

            TransactionDetailRepository::create([
                'transaction_id'    => $transaction->id,
                'name'              => $this->request->name,
                'start'             => $this->request->start_date,
                'end'               => $this->request->end_date,
                'villa_name'        => $villa->name,
                'villa_address'     => $villa->city->address,
                'villa_price'       => $villa->price,
            ]);

            // schedule
            foreach ($date_booking as $date) {
                VillaScheduleRepository::create([
                    'villa_id'          => $villa->id,
                    'transaction_id'    => $transaction->id,
                    'date'              => $date,
                ]);
            }

            $this->data['code'] = $transaction->code;

            if ($villa->seller->fcm_token) {
                (new FirebaseRepository)->send($villa->seller->fcm_token, "Booking", "Booking masuk untuk {$villa->name}");
            }

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private static function generateCode() : string {
        $prefix         = "RJVL";
        $random_string  = substr(md5(mt_rand()), 0, 9);
        $code       = "$prefix-$random_string";

        $check = TransactionRepository::first(['code' => $code]);
        if ($check) self::generateCode();

        return strtoupper("$prefix-$random_string");
    }
}
