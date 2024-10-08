<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Interface\RepositoryApi;
use App\Mail\Verification\VerificationMail;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Register extends Service
{
    const CONTEXT           = "register";
    const MESSAGE_SUCCESS   = "berhasil register, silahkan cek email untuk melakukan verifikasi";
    const MESSAGE_ERROR     = "gagal register";

    public function __construct(protected Request $request, protected string $user_type)
    {
    }

    private function rulesValidator(): array
    {
        switch ($this->user_type) {
            case MyConst::USER_SELLER:
                $rules = [
                    'name'          => ['required', 'string'],
                    'email'         => ['required', 'email', 'unique:investors,email'],
                    'phone'         => ['required', 'numeric', 'unique:investors,phone'],
                    'password'      => ['required', 'min:8'],
                    'gender'        => ['required', Rule::in(MyConst::GENDER)],
                    'birth_date'    => ['required', 'date_format:Y-m-d'],
                    'nik'           => ['required', 'size:16', 'unique:investors,nik'],
                ];
                break;

            case MyConst::USER_BUYER:
                $rules = [
                    'name'          => ['required', 'string'],
                    'email'         => ['required', 'email', 'unique:buyers,email'],
                    'phone'         => ['required', 'numeric', 'unique:buyers,phone'],
                    'password'      => ['required', 'min:8'],
                    'gender'        => ['required', Rule::in(MyConst::GENDER)],
                    'birth_date'    => ['required', 'date_format:Y-m-d'],
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
        DB::beginTransaction();
        try {
            $message = self::MESSAGE_SUCCESS;

            if (!$this->validateUserType()) {
                DB::rollBack();
                return parent::error("tipe user tidak valid");
            }

            $validator = parent::validator($this->request->all(), $this->rulesValidator());
            if ($validator->fails()) {
                DB::rollBack();
                return parent::error($validator->errors()->first());
            }

            $data = $validator->validated();

            if ($this->user_type == MyConst::USER_SELLER) {
                $data['email_verified_at']      = now();
                $data['document_verified_at']   = now();

                $message = "berhasil mendaftarkan akun";
            }

            $repo = $this->repo();
            $user = $repo->create($data);

            // send email
            if ($this->user_type == MyConst::USER_BUYER) {
                VerificationMail::send($user);
            }

            DB::commit();
            return parent::success($message, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
