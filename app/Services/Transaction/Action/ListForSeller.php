<?php

namespace App\Services\Transaction\Action;

use App\Base\Service;
use App\Models\DTO\SearchTransaction;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ListForSeller extends Service
{
    const CONTEXT           = "memuat transaksi";
    const MESSAGE_SUCCESS   = "berhasil memuat transaksi";
    const MESSAGE_ERROR     = "gagal memuat transaksi";

    const RULES_VALIDATOR = [
        'code'      => 'sometimes|nullable|string',
        'status'    => 'sometimes|nullable|integer',
        'villa_id'  => 'sometimes|nullable|string',
        'start_date'=> ['sometimes', 'nullable', 'date_format:Y-m-d'],
        'end_date'  => ['sometimes', 'nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
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
            $param->status      = $this->request->status;
            $param->start_date  = $this->request->start_date ? Carbon::parse($this->request->start_date)->format('Y-m-d 00:00:00') : null;
            $param->end_date    = $this->request->end_date ? Carbon::parse($this->request->end_date)->format('Y-m-d 23:59:59') : null;
            $param->villa_id    = $this->request->villa_id;

            $transaction = TransactionRepository::listForSeller($this->seller->id, $param, $this->cursor);

            $this->data = [
                'result'    => self::map($transaction),
                'next'      => $transaction->nextCursor()?->encode(),
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
    
    static function map($transactions) : array {
        return $transactions->map(function(Transaction $transaction){
            return [
                'id'            => $transaction->id,
                'code'          => $transaction->code,
                'amount'        => $transaction->amount,
                'image'         => $transaction->villa->file->local_path,
                'created_at'    => $transaction->created_at->translatedFormat('j F Y'),
            ];
        })->toArray();
    }
}
