<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\DestinationRepository;
use Illuminate\Http\Response;

final class Delete extends Service
{
    const CONTEXT           = "menghapus destinasi";
    const MESSAGE_SUCCESS   = "berhasil menghapus destinasi";
    const MESSAGE_ERROR     = "gagal menghapus destinasi";

    public function __construct(protected int $id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $destination = DestinationRepository::first(['id' => $this->id]);
            if (!$destination) return parent::error("data destinasi tidak valid", Response::HTTP_BAD_REQUEST);

            DestinationRepository::delete($this->id);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
