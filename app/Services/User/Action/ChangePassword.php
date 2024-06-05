<?php

namespace App\Services\User\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

final class ChangePassword extends Service
{
    const CONTEXT           = "memperbarui password";
    const MESSAGE_SUCCESS   = "berhasil memperbarui password";
    const MESSAGE_ERROR     = "gagal memperbarui password";

    const RULES_VALIDATOR = [
        'password_old' => 'required|string',
        'password_new' => 'required|string',
    ];

    const ATTRIBUTES_VALIDATOR = [
        'password_old' => 'password lama',
        'password_new' => 'password baru',
    ];

    public function __construct(protected Request $request, protected User $user)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR, [], self::ATTRIBUTES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $match = Hash::check($this->request->password_old, $this->user->password);
            if (!$match) return parent::error("password lama tidak valid", Response::HTTP_BAD_REQUEST);

            UserRepository::update($this->user->id, [
                'password' => Hash::make($this->request->password_new)
            ]);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
