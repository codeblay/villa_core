<?php

namespace App\Services\User\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Store extends Service
{
    const CONTEXT           = "menyimpan agent";
    const MESSAGE_SUCCESS   = "berhasil menyimpan agent";
    const MESSAGE_ERROR     = "gagal menyimpan agent";

    const RULES_VALIDATOR = [
        'name'      => 'required|string',
        'email'     => 'required|email|unique:users',
        'password'  => 'required|string',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $data                       = $validator->validated();
            $data['email_verified_at']  = now();
            $data['is_admin']           = false;

            UserRepository::create($data);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            dd($th);
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
