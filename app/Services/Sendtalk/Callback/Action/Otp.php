<?php

namespace App\Services\Sendtalk\Callback\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Otp as ModelsOtp;
use App\Repositories\OtpRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Otp extends Service
{
    const CONTEXT           = "receive callback";
    const MESSAGE_SUCCESS   = "success receive callback";
    const MESSAGE_ERROR     = "failed receive callback";

    function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        $id             = $this->request->id;
        $phone          = $this->request->userPhone;
        $status         = $this->request->status;
        $verified_at    = $this->request->verifiedTime;

        if ($status == "verified") {
            $otp = OtpRepository::first(['phone' => $phone]);
            OtpRepository::update($otp->id, ['status' => ModelsOtp::STATUS_VALID]);
        } else {
            logger()->channel('sendtalk')->info("callback otp", $this->request->all());
        }

        return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
    }
}
