<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CancelForgotPassword extends Service
{
    const CONTEXT           = "reset password";
    const MESSAGE_SUCCESS   = "berhasil reset password";
    const MESSAGE_ERROR     = "gagal reset password";


    public function __construct(protected Request $request)
    {
    }

    const RULES_VALIDATOR = [
        'token' => 'required|string',
    ];

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());
            
            $user = SellerRepository::first(['reset_token' => $this->request->token]);
            
            if (!$user) {
                $user = BuyerRepository::first(['reset_token' => $this->request->reset_token]);
                if (!$user) return parent::error('user tidak ditemukan');
            }

            $repo = new SellerRepository;
            if ($user instanceof Buyer) $repo = new BuyerRepository;

            $repo->update($user->id, [
                'reset_token' => null,
            ]);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
