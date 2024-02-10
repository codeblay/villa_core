<?php

namespace App\Services\API\Auth\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Logout extends Service
{
    const CONTEXT           = "logout";
    const MESSAGE_SUCCESS   = "success logout";
    const MESSAGE_ERROR     = "failed logout";

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $repo = AuthRepository::revokeCurrentToken($this->request->user());
            if (!$repo) return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
