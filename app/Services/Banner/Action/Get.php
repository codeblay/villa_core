<?php

namespace App\Services\Banner\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use Illuminate\Http\Response;

final class Get extends Service
{
    const CONTEXT           = "memuat banner";
    const MESSAGE_SUCCESS   = "berhasil memuat banner";
    const MESSAGE_ERROR     = "gagal memuat banner";

    public function __construct()
    {
    }

    function call(): ServiceResponse
    {
        try {
            $file = "banner/" . MyConst::BANNER_NAME;
        
            if (!file_exists(public_path($file))) return parent::error("banner tidak ditemukan");

           $this->data = [
            'image' => asset($file)
           ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
