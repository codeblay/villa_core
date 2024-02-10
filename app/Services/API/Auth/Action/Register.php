<?php

namespace App\Services\API\Auth\Action;

use App\Base\Service;
use App\Interface\RepositoryApi;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Register extends Service
{
    const CONTEXT           = "register";
    const MESSAGE_SUCCESS   = "success register";
    const MESSAGE_ERROR     = "failed register";

    public function __construct(protected Request $request, protected string $user_type)
    {
    }

    private function rulesValidator(): array
    {
        switch ($this->user_type) {
            case MyConst::USER_SELLER:
                $rules = [
                    'name'          => ['required', 'string'],
                    'email'         => ['required', 'email', 'unique:sellers,email'],
                    'password'      => ['required', 'min:8'],
                    'gender'        => ['required', 'in:Pria,Wanita'],
                    'birth_date'    => ['required', 'date_format:Y-m-d'],
                    'nik'           => ['required', 'size:16', 'unique:sellers,nik'],
                ];
                break;

            case MyConst::USER_BUYER:
                $rules = [
                    'name'          => ['required', 'string'],
                    'email'         => ['required', 'email', 'unique:buyers,email'],
                    'password'      => ['required', 'min:8'],
                    'gender'        => ['required', 'in:Pria,Wanita'],
                    'birth_date'    => ['required', 'date_format:Y-m-d'],
                    'nik'           => ['required', 'size:16', 'unique:buyers,nik'],
                ];
                break;
        }

        return $rules;
    }

    private function repo(): RepositoryApi
    {
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

    private function validateUserType(): bool
    {
        return in_array($this->user_type, config('user.type'));
    }

    function call(): ServiceResponse
    {
        try {
            if (!$this->validateUserType()) return parent::error("tipe user tidak valid");

            $validator = parent::validator($this->request->all(), $this->rulesValidator());
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $repo = $this->repo();
            $seller = $repo->create($validator->validated());
            
            $this->data['token'] = $repo->token($seller);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
