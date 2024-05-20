<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaInvestorRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class AddInvestor extends Service
{
    const CONTEXT           = "menyimpan investor";
    const MESSAGE_SUCCESS   = "berhasil menyimpan investor";
    const MESSAGE_ERROR     = "gagal menyimpan investor";

    const RULES_VALIDATOR = [
        'villa_id'      => 'required|numeric',
        'investor_id'   => 'required|numeric',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $check = VillaInvestorRepository::first($validator->validated());
            if ($check) return parent::error("Investor sudah ditambahkan");

            VillaInvestorRepository::create($validator->validated());

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
