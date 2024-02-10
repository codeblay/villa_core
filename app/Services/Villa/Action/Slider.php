<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Villa;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;

final class Slider extends Service
{
    const CONTEXT           = "load villa";
    const MESSAGE_SUCCESS   = "success load villa";
    const MESSAGE_ERROR     = "failed load villa";

    private int $total = 3;

    function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    function call(): ServiceResponse
    {
        try {
            $repo = VillaRepository::limit($this->total);

            $this->data = $repo->map(function (Villa $villa) {
                return [
                    'id'        => $villa->id,
                    'name'      => $villa->name,
                    'address'   => $villa->city->address,
                ];
            })->toArray();

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
