<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Villa;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;

final class Detail extends Service
{
    const CONTEXT           = "load villa";
    const MESSAGE_SUCCESS   = "success load villa";
    const MESSAGE_ERROR     = "failed load villa";

    public function __construct(protected int $villa_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $villa = VillaRepository::detailForBuyer($this->villa_id);
            if (!$villa) return parent::error('villa not found', Response::HTTP_BAD_REQUEST);

            $this->data = self::mapResult($villa);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private static function mapResult(Villa $villa): array
    {
        return [
            'id'            => $villa->id,
            'name'          => $villa->name,
            'address'       => $villa->city->address,
            'price'         => $villa->price,
            'description'   => $villa->description,
            'facilities'    => $villa->facilities->pluck('name')->toArray(),
        ];
    }
}
