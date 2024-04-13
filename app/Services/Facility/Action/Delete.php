<?php

namespace App\Services\Facility\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\FacilityRepository;
use Illuminate\Http\Response;

final class Delete extends Service
{
    const CONTEXT           = "menghapus fasilitas";
    const MESSAGE_SUCCESS   = "berhasil menghapus fasilitas";
    const MESSAGE_ERROR     = "gagal menghapus fasilitas";

    public function __construct(protected int $id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $facility = FacilityRepository::first(['id' => $this->id]);
            if (!$facility) return parent::error("data fasilitas tidak valid", Response::HTTP_BAD_REQUEST);

            if (count($facility->villas) > 0) return parent::error("fasilitas telah digunakan oleh beberapa villa", Response::HTTP_BAD_REQUEST);

            FacilityRepository::delete($this->id);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
