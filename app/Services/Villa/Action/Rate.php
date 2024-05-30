<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaRatingRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Rate extends Service
{
    const CONTEXT           = "rate villa";
    const MESSAGE_SUCCESS   = "berhasil rate villa";
    const MESSAGE_ERROR     = "gagal rate villa";

    const RULES_VALIDATOR = [
        'transaction_id'    => 'required|integer',
        'rate'              => 'required|integer|min:1|max:5',
    ];

    public function __construct(protected Request $request, protected Buyer $buyer)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $transaction = TransactionRepository::first([
                'id'                => $this->request->transaction_id,
                'buyer_id'          => $this->buyer->id,
                'status'            => Transaction::STATUS_SUCCESS,
            ]);
            if (!$transaction) return parent::error("booking terlebih dahulu");

            $rating = VillaRatingRepository::first(['transaction_id' => $transaction->id]);
            if ($rating) {
                VillaRatingRepository::update($rating->id, [
                    'rating' => $this->request->rate,
                ]);
            } else {
                VillaRatingRepository::create([
                    'villa_id'          => $transaction->villaType->villa_id,
                    'buyer_id'          => $this->buyer->id,
                    'transaction_id'    => $transaction->id,
                    'rating'            => $this->request->rate,
                ]);
            }


            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
