<?php

namespace App\Services\Banner\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Upload extends Service
{
    const CONTEXT           = "menyimpan banner";
    const MESSAGE_SUCCESS   = "berhasil menyimpan banner";
    const MESSAGE_ERROR     = "gagal menyimpan banner";

    const RULES_VALIDATOR = [
        'banner' => 'required|mimes:jpg'
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $banner = $this->request->file('banner');

            $banner->move('banner', MyConst::BANNER_NAME);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
