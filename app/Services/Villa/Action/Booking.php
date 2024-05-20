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
use App\Repositories\VillaTypeRepository;
use App\Services\Midtrans\Transaction\MidtransTransactionService;
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
            'villa_type_id' => ['required', 'integer'],
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

            $villa_type = VillaTypeRepository::first(['id' => $this->request->villa_type_id]);
            if (!$villa_type || !$villa_type->is_publish || !$villa_type->villa->is_publish) {
                DB::rollBack();
                return parent::error("data villa tidak ditemukan");
            }

            $date_booking       = CarbonPeriod::create($this->request->start_date, $this->request->end_date);
            $booked_schedules   = VillaScheduleRepository::schedule($this->request->villa_type_id, $date_booking);

            foreach ($date_booking as $date) {
                $date_format = $date->format('Y-m-d');
                $booked = $booked_schedules->where('date', $date_format);

                if ($booked->count() == $villa_type->total_unit) {
                    return parent::error("villa telah di-booking pada tanggal {$date_format}");
                }
            }

            $bank = BankRepository::first(['code' => $this->request->payment]);

            $transaction = TransactionRepository::create([
                'code'              => self::generateCode(),
                'villa_type_id'     => $villa_type->id,
                'buyer_id'          => $this->buyer->id,
                'bank_id'           => $bank->id,
                'status'            => Transaction::STATUS_PENDING,
                'amount'            => $villa_type->price * count($date_booking),
                'fee'               => $bank->fee,
            ]);

            TransactionDetailRepository::create([
                'transaction_id'    => $transaction->id,
                'name'              => $this->request->name,
                'start'             => $this->request->start_date,
                'end'               => $this->request->end_date,
                'villa_name'        => $villa_type->full_name,
                'villa_address'     => $villa_type->villa->city->address,
                'villa_price'       => $villa_type->price,
            ]);

            // schedule
            foreach ($date_booking as $date) {
                VillaScheduleRepository::create([
                    'villa_type_id'     => $villa_type->id,
                    'transaction_id'    => $transaction->id,
                    'date'              => $date,
                ]);
            }

            $midtrans = MidtransTransactionService::create($transaction);
            if (!$midtrans->status) {
                DB::rollBack();
                return parent::error("terjadi kesalahan, cobalah beberapa saat lagi", Response::HTTP_BAD_GATEWAY);
            }

            // if ($transaction->buyer->fcm_token) {
            //     (new FirebaseRepository)->send($transaction->buyer->fcm_token, "Booking Berhasil", "Booking diterima dengan kode booking {$transaction->code}");
            // }

            $this->data['code'] = $transaction->code;

            // if ($villa_type->seller->fcm_token) {
            //     (new FirebaseRepository)->send($villa_type->seller->fcm_token, "Booking", "Booking masuk untuk {$villa_type->name}");
            // }

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
