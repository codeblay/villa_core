<?php

namespace App\Services\Verification\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Email extends Service
{
    const CONTEXT           = "register";
    const MESSAGE_SUCCESS   = "berhasil register, silahkan cek email untuk melakukan verifikasi";
    const MESSAGE_ERROR     = "gagal register";

    public function __construct(protected Request $request)
    {
    }

    const RULES_VALIDATOR = [
        'token' => 'required|string',
    ];

    private function repo(string $type): SellerRepository|BuyerRepository
    {
        switch ($type) {
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

            [$type, $email, $created_at] = $this->decryptToken();

            $repo = $this->repo($type);

            $user = $repo->first([
                'email'         => $email,
                'created_at'    => $created_at,
            ]);

            if (!$user) return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);

            $repo->update($user->id, ['email_verified_at' => now()]);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private function decryptToken(): array
    {
        $d = explode('||', base64_decode(decrypt($this->request->token)));
        return [
            $d[0],
            $d[1],
            Carbon::createFromTimestamp($d[2])->format('Y-m-d H:i:s'),
        ];
    }
}
