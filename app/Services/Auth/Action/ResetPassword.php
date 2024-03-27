<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Interface\RepositoryApi;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Service
{
    const CONTEXT           = "reset password";
    const MESSAGE_SUCCESS   = "berhasil reset password";
    const MESSAGE_ERROR     = "gagal reset password";

    const RULES_VALIDATOR = [
        'old_password'  => 'required|string',
        'new_password'  => 'required|string',
    ];

    public function __construct(protected Request $request, protected Seller|Buyer $user)
    {
    }

    private function repo() : RepositoryApi {

        if ($this->user instanceof Seller) $repo = (new SellerRepository);
        if ($this->user instanceof Buyer) $repo = (new BuyerRepository);

        return $repo;
    }
    
    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) {
                DB::rollBack();
                return parent::error($validator->errors()->first());
            }

            
            $validate_password = Hash::check($this->request->old_password, @$this->user->password);
            if (!$validate_password) {
                DB::rollBack();
                return parent::error("password salah");
            }

            $check_password = $this->request->old_password == $this->request->new_password;
            if ($check_password) {
                DB::rollBack();
                return parent::error("password baru tidak boleh sama dengan password lama");
            }

            $this->repo()->update($this->user->id, [
                'password' => Hash::make($this->request->new_password)
            ]);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
