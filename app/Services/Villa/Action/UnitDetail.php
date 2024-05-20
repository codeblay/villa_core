<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\VillaType;
use App\Repositories\VillaTypeRepository;
use Illuminate\Http\Response;

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

            $this->data = $this->mapResult($villa);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    static function mapResult(VillaType $unit): array
    {
        return [
            'id'            => $unit->id,
            'name'          => $unit->name,
            'price'         => $unit->price,
            'description'   => $unit->description,
            'rating'        => $unit->rating,
            'image_url'     => $unit->primaryImage->local_path,
        ];
    }
}
