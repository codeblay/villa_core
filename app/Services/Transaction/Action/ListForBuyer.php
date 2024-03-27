<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ListForBuyer extends Service
{
    const CONTEXT           = "load transaction";
    const MESSAGE_SUCCESS   = "berhasil load transaction";
    const MESSAGE_ERROR     = "gagal load transaction";

    const RULES_VALIDATOR = [
        'status' => 'nullable|integer'
    ];

    private int $cursor = 10;

    function __construct(protected Request $request, protected Buyer $buyer)
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

            $transaction = TransactionRepository::listForBuyer($this->buyer->id, $this->request->status, $this->cursor);

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
