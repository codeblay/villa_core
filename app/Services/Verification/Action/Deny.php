<?php

namespace App\Services\Auth\Verification;

use App\Base\Service;
use App\Mail\Verifiaction\VerificationMail;
use App\Models\DTO\ServiceResponse;
use App\Repositories\SellerRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Deny extends Service
{
    const CONTEXT           = "menolak verifikasi";
    const MESSAGE_SUCCESS   = "berhasil menolak verifikasi";
    const MESSAGE_ERROR     = "gagal menolak verifikasi";

    public function __construct(protected int $seller_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $seller = SellerRepository::first(['id' => $this->seller_id]);
            if (!$seller) return parent::error("akun tidak ditemukan");

            VerificationMail::deny($seller);
            SellerRepository::delete($seller->id);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
