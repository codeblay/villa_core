<?php

namespace App\Services\Bank\Action;

use App\Base\Service;
use App\Models\Bank;
use App\Models\DTO\ServiceResponse;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Update extends Service
{
    const CONTEXT           = "menyimpan fasilitas";
    const MESSAGE_SUCCESS   = "berhasil menyimpan fasilitas";
    const MESSAGE_ERROR     = "gagal menyimpan fasilitas";

    private function rulesValidator() : array {
        $rules = [
            'id'        => 'required|integer',
            'is_active' => 'required|boolean',
        ];

        if ($this->request->code != Bank::QR) {
            $rules['va_number'] = 'required|numeric';
        }

        return $rules;
    }

    const RULES_ATTRIBUTES = [
        'is_active' => 'status',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), $this->rulesValidator(), attributes: self::RULES_ATTRIBUTES);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            BankRepository::update($this->request->id, $validator->validated());

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
