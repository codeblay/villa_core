<?php

namespace App\Services\Bank\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Update extends Service
{
    const CONTEXT           = "menyimpan fasilitas";
    const MESSAGE_SUCCESS   = "berhasil menyimpan fasilitas";
    const MESSAGE_ERROR     = "gagal menyimpan fasilitas";

    const RULES_VALIDATOR = [
        'id'        => 'required|integer',
        'va_number' => 'required|numeric',
        'is_active' => 'required|boolean',
    ];

    const RULES_ATTRIBUTES = [
        'is_active' => 'status',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR, attributes: self::RULES_ATTRIBUTES);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            BankRepository::update($this->request->id, $validator->validated());

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
