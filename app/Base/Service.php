<?php

namespace App\Base;

use App\Interface\Service as InterfaceService;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Throwable;

abstract class Service implements InterfaceService
{
    protected array $data = [];

    protected function validator(array $data, array $rules, array $messages = [], array $attributes = []) : Validator {
        return validator($data, $rules, $messages, $attributes);
    }

    protected function success(string $message, int $code = Response::HTTP_OK): ServiceResponse
    {
        $response               = new ServiceResponse;
        $response->status       = true;
        $response->message      = $message;
        $response->data         = $this->data;
        $response->code         = $code;

        return $response;
    }

    protected function error(string $message, int $code = Response::HTTP_BAD_REQUEST): ServiceResponse
    {
        $response               = new ServiceResponse;
        $response->status       = false;
        $response->message      = $message;
        $response->data         = $this->data;
        $response->code         = $code;

        return $response;
    }

    protected function storeLog(Throwable $th, string $context): void
    {
        logger()->error($context, [
            'file'      => $th->getFile(),
            'message'   => $th->getMessage(),
            'line'      => $th->getLine(),
        ]);
    }

    protected function isSeller() : bool {
        return auth()?->user() instanceof Seller;
    }

    protected function isBuyer() : bool {
        return auth()?->user() instanceof Buyer;
    }
}
