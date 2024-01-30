<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use Illuminate\Http\Response;

final class Create extends Service
{
    const CONTEXT           = "create villa";
    const MESSAGE_SUCCESS   = "success create villa";
    const MESSAGE_ERROR     = "failed create villa";

    function call(): ServiceResponse
    {
        try {
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
