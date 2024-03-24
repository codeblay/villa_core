<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Interface\RepositoryApi;
use App\Mail\VerificationMail;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use App\Services\Otp\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
                    'email'         => ['required', 'email', 'unique:sellers,email'],
                    'phone'         => ['required', 'numeric', 'unique:sellers,phone'],
                    'password'      => ['required', 'min:8'],
                    'gender'        => ['required', Rule::in(MyConst::GENDER)],
                    'birth_date'    => ['required', 'date_format:Y-m-d'],
                    'nik'           => ['required', 'size:16', 'unique:sellers,nik'],
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
            if (!$this->validateUserType()) {
                DB::rollBack();
                return parent::error("tipe user tidak valid");
            }

            $validator = parent::validator($this->request->all(), $this->rulesValidator());
            if ($validator->fails()) {
                DB::rollBack();
                return parent::error($validator->errors()->first());
            }

            if ($this->user_type == MyConst::USER_SELLER) {
                $document       = "pdf/" . MyConst::DOCUMENT_VERIFICATION_NAME;
                $document_exist = file_exists(public_path($document));
                if (!$document_exist) {
                    return parent::error("sedang terjadi kendala teknis", Response::HTTP_PRECONDITION_REQUIRED);
                }
            }

            $repo = $this->repo();
            $user = $repo->create($validator->validated());

            // $send_otp = OtpService::send($this->request->phone);
            // if (!$send_otp->status) {
            //     DB::rollBack();
            //     return parent::success($send_otp->message, $send_otp->code);
            // }

            // send email
            Mail::send(new VerificationMail($user));

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
