<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaTypeRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class Unit extends Service
{
    const CONTEXT           = "memuat unit";
    const MESSAGE_SUCCESS   = "berhasil memuat unit";
    const MESSAGE_ERROR     = "gagal memuat unit";

    function __construct(protected int $villa_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $villa = VillaTypeRepository::get([
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

    static function mapResult(Collection $data): array
    {
        foreach ($data as $unit) {
            $result[] = [
                'id'            => $unit->id,
                'name'          => $unit->name,
                'price'         => $unit->price,
                'description'   => $unit->description,
                'rating'        => $unit->rating,
                'image_url'     => $unit->files->pluck('local_path')->toArray(),
            ];
        }
        return $result ?? [];
    }
}
