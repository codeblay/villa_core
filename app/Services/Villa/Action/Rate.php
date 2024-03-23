<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaRatingRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Rate extends Service
{
    const CONTEXT           = "rate villa";
    const MESSAGE_SUCCESS   = "success rate villa";
    const MESSAGE_ERROR     = "failed rate villa";

    const RULES_VALIDATOR = [
        'villa_id'      => 'required|integer',
        'rate'          => 'required|integer|min:1|max:5',
    ];

    public function __construct(protected Request $request, protected Buyer $buyer)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $rating = VillaRatingRepository::first([
                'villa_id' => $this->request->villa_id,
                'buyer_id' => $this->request->buyer_id,
            ]);
            if ($rating) return parent::error("villa telah di rating");

            VillaRatingRepository::create([
                'villa_id'     => $this->request->villa_id,
                'buyer_id'     => $this->buyer->id,
                'rating'       => $this->request->rate,
            ]);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
