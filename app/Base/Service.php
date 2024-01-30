<?php

namespace App\Base;

use App\Interface\Service as InterfaceService;
use App\Models\DTO\ServiceResponse;
use Illuminate\Http\Response;
use Throwable;

abstract class Service implements InterfaceService
{
    protected array $result = [];

    function success(string $message, int $code = Response::HTTP_OK): ServiceResponse
    {
        $response               = new ServiceResponse;
        $response->status       = true;
        $response->message      = $message;
        $response->result       = $this->result;
        $response->code         = $code;

        return $response;
    }

    function error(string $message, int $code = Response::HTTP_BAD_REQUEST): ServiceResponse
    {
        $response               = new ServiceResponse;
        $response->status       = false;
        $response->message      = $message;
        $response->result       = $this->result;
        $response->code         = $code;

        return $response;
    }

    function storeLog(Throwable $th, string $context): void
    {
        logger()->error($context, [
            'file'      => $th->getFile(),
            'message'   => $th->getMessage(),
            'line'      => $th->getLine(),
        ]);
    }
}
