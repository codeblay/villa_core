<?php

namespace App\Services\City\Action;

use App\Base\Service;
use App\Models\City;
use App\Models\DTO\ServiceResponse;
use App\Repositories\CityRepository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

final class Dropdown extends Service
{
    const CONTEXT           = "memuat daerah";
    const MESSAGE_SUCCESS   = "berhasil memuat daerah";
    const MESSAGE_ERROR     = "gagal memuat daerah";

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $cities = CityRepository::search($this->request->keyword ?? '');
            $this->data = $cities->map(function(City $city) {
                return [
                    "id"    => $city->id,
                    "name"  => $city->address,
                ];
            })->toArray();

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
