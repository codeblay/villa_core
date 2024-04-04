<?php

namespace App\Services\Profile\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use Carbon\Carbon;
use Illuminate\Http\Response;

final class ProfileSeller extends Service
{
    const CONTEXT           = "memuat profile";
    const MESSAGE_SUCCESS   = "berhasil memuat profile";
    const MESSAGE_ERROR     = "gagal memuat profile";

    public function __construct()
    {
    }

    function call(): ServiceResponse
    {
        try {
            $seller = auth()->user();
            if (!$seller) {
                return parent::error("terjadi kesalahan saat mengakses profil");
            }

            $this->data = [
                'name'          => $seller->name,
                'email'         => $seller->email,
                'phone'         => $seller->phone,
                'gender'        => $seller->gender_label,
                'birth_date'    => Carbon::parse($seller->birth_date)->format('d-m-Y'),
                'nik'           => $seller->nik,
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
