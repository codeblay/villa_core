<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Interface\RepositoryApi;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\MyConst;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class Login extends Service
{
    const CONTEXT           = "login";
    const MESSAGE_SUCCESS   = "success login";
    const MESSAGE_ERROR     = "failed login";

    const RULES_VALIDATOR = [
        'email'     => 'required|email',
        'password'  => 'required|string',
    ];

    public function __construct(protected Request $request, protected string $user_type)
    {
        $this->data['token'] = '';
    }

    private function validateUserType() : bool {
        return in_array($this->user_type, config('user.type'));
    }

    private function repo() : RepositoryApi {
        switch ($this->user_type) {
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
            if (!$this->validateUserType()) return parent::error("tipe user tidak valid");

            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $repo = $this->repo();
            $user = $repo::first(['email' => $this->request->email]);
            if (!@$user) return parent::error('email atau password salah');

            $check_password = Hash::check($this->request->password, @$user->password);
            if (!$check_password) return parent::error('email atau password salah');

            if (!$user->is_verified) return parent::error('akun anda belum diverifikasi', Response::HTTP_UNAUTHORIZED);

            $token = $repo::token($user);
            if (empty($token)) return parent::error(self::MESSAGE_ERROR);

            $this->data['token'] = $token;

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);            
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
