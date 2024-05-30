<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Transaction;
use App\Models\VillaType;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaTypeRepository;
use Illuminate\Http\Response;
use Illuminate\Pagination\CursorPaginator;

final class UnitDetail extends Service
{
    const CONTEXT           = "memuat unit";
    const MESSAGE_SUCCESS   = "berhasil memuat unit";
    const MESSAGE_ERROR     = "gagal memuat unit";

    function __construct(protected int $villa_id, protected int $unit_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $villa = VillaTypeRepository::first([
                'id'            => $this->unit_id,
                'villa_id'      => $this->villa_id,
                'is_publish'    => true
            ]);

            $trx = TransactionRepository::listByUnit($this->unit_id, 10);

            $this->data = $this->mapResult($villa, $trx);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    static function mapResult(VillaType $unit, CursorPaginator $trx): array
    {
        return [
            'id'            => $unit->id,
            'name'          => $unit->name,
            'price'         => $unit->price,
            'rating'        => $unit->rating,
            'transactions'  => collect($trx->items())->map(function(Transaction $t){
                return [
                    'code'          => $t->code,
                    'status'        => $t->status_label,
                    'created_at'    => $t->created_at,
                ];
            }),
            'next'        => $trx->nextCursor()?->encode()
        ];
    }
}
