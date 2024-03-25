<?php

namespace App\Services\Verification;

use App\Base\Service;
use App\Mail\Verification\VerificationMail;
use App\Models\DTO\ServiceResponse;
use App\Repositories\SellerRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Accept extends Service
{
    const CONTEXT           = "terima verifikasi";
    const MESSAGE_SUCCESS   = "berhasil terima verifikasi";
    const MESSAGE_ERROR     = "gagal terima verifikasi";

    public function __construct(protected int $seller_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $seller = SellerRepository::first(['id' => $this->seller_id]);
            if (!$seller) return parent::error("akun tidak ditemukan");

            SellerRepository::update($seller->id, [
                'document_verified_at' => now()
            ]);

            VerificationMail::accept($seller);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
