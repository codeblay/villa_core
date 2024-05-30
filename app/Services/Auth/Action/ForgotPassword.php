<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Mail\Account\AccountMail;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ForgotPassword extends Service
{
    const CONTEXT           = "request password";
    const MESSAGE_SUCCESS   = "berhasil request password, silahkan cek email";
    const MESSAGE_ERROR     = "gagal request password";

    const RULES_VALIDATOR = [
        'email' => 'required|email',
    ];

    public function __construct(protected Request $request, protected string $user_type)
    {
    }

    private function repo(): SellerRepository|BuyerRepository
    {
        switch ($this->user_type) {
            case "seller":
            case MyConst::USER_SELLER:
                $repo = (new SellerRepository);
                break;
            case MyConst::USER_BUYER:
                $repo = (new BuyerRepository);
                break;
        }

        return $repo;
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());
            
            $user = $this->repo()->first(['email' => $this->request->email]);
            if (!$user) return parent::error('email tidak terdaftar');

            $this->repo()->update($user->id, [
                'reset_token' => $this->generateToken(),
            ]);

            $user = $this->repo()->first(['id' => $user->id]);

            AccountMail::forgotPassword($user);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private function generateToken() : string {
        $token = Hash::make(config('app.key'));

        $user = $this->repo()->first(['reset_token' => $token]);
        if ($user) return $this->generateToken();

        return $token;
    }
}
