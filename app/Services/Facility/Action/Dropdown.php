<?php

namespace App\Services\Facility\Action;

use App\Base\Service;
use App\Models\DTO\SearchFacility;
use App\Models\DTO\ServiceResponse;
use App\Models\Facility;
use App\Repositories\FacilityRepository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

final class Dropdown extends Service
{
    const CONTEXT           = "memuat fasilitas";
    const MESSAGE_SUCCESS   = "berhasil memuat fasilitas";
    const MESSAGE_ERROR     = "gagal memuat fasilitas";

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $param          = new SearchFacility();
            $param->name    = $this->request['keyword'];

            $facilites = FacilityRepository::search($param);
            $this->data = $facilites->map(function(Facility $facility) {
                return [
                    "id"    => $facility->id,
                    "name"  => $facility->name,
                ];
            })->toArray();

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
