<?php

namespace App\Services\Payment\Action;

use App\Base\Service;
use App\Models\Bank;
use App\Models\DTO\ServiceResponse;
use App\Repositories\BankRepository;
use Illuminate\Http\Response;

final class Get extends Service
{
    const CONTEXT           = "memuat payment";
    const MESSAGE_SUCCESS   = "berhasil memuat payment";
    const MESSAGE_ERROR     = "gagal memuat payment";

    function call(): ServiceResponse
    {
        try {
            $payments = BankRepository::get(['is_active' => true]);
            $this->data = $payments->map(function(Bank $bank){
                return [
                    'id'        => $bank->id,
                    'name'      => $bank->name,
                    'code'      => $bank->code,
                    'va_number' => $bank->va_number,
                ];
            })->toArray();

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
