<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\SearchTransaction;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ListForSeller extends Service
{
    const CONTEXT           = "load transaction";
    const MESSAGE_SUCCESS   = "success load transaction";
    const MESSAGE_ERROR     = "failed load transaction";

    const RULES_VALIDATOR = [
        'code'      => 'sometimes|nullable|string',
        'created_at'=> 'sometimes|nullable|date_format:Y-m-d',
        'status'    => 'nullable|integer',
    ];

    private int $cursor = 10;

    function __construct(protected Request $request, protected Seller $seller)
    {
    }

    function setCursor(int $cursor): self
    {
        $this->cursor = $cursor;
        return $this;
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $param              = new SearchTransaction;
            $param->code        = $this->request->code;
            $param->created_at  = $this->request->created_at;
            $param->status      = $this->request->status;
            
            $transaction = TransactionRepository::listForSeller($this->seller->id, $param, $this->cursor);

            $this->data = [
                'result'    => $transaction->items(),
                'next'      => $transaction->nextCursor()?->encode(),
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
