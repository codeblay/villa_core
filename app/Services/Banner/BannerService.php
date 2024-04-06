<?php

namespace App\Services\Banner;

use App\Models\DTO\ServiceResponse;
use App\Services\Banner\Action\Upload;
use App\Services\Banner\Action\Get;
use App\Services\Banner\Action\Delete;
use Illuminate\Http\Request;

class BannerService
{
    static function get(): ServiceResponse
    {
        return (new Get)->call();
    }

    static function upload(Request $request): ServiceResponse
    {
        return (new Upload($request))->call();
    }

    static function delete(): ServiceResponse
    {
        return (new Delete())->call();
    }
}
