<?php

namespace App\Services\Banner\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use Illuminate\Http\Response;

final class Delete extends Service
{
    const CONTEXT           = "menghapus banner";
    const MESSAGE_SUCCESS   = "berhasil menghapus banner";
    const MESSAGE_ERROR     = "gagal menghapus banner";

    public function __construct()
    {
    }

    function call(): ServiceResponse
    {
        try {
            $file = "banner/" . MyConst::BANNER_NAME;
        
            if (!file_exists(public_path($file))) return parent::error("banner tidak ditemukan");

           unlink($file);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
