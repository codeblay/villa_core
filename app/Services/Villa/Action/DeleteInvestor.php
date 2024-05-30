<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaInvestorRepository;
use Illuminate\Http\Response;

final class DeleteInvestor extends Service
{
    const CONTEXT           = "menghapus investor";
    const MESSAGE_SUCCESS   = "berhasil menghapus investor";
    const MESSAGE_ERROR     = "gagal menghapus investor";

    public function __construct(protected int $id)
    {
    }

    function call(): ServiceResponse
    {
        try {

            VillaInvestorRepository::delete($this->id);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
