<?php

namespace App\Services\Otp\Action;

use App\Base\Service;
use App\Models\DTO\Sendtalk\Message;
use App\Models\DTO\Sendtalk\Verification;
use App\Models\DTO\ServiceResponse;
use App\Models\Otp;
use App\Repositories\OtpRepository;
use App\Repositories\SendtalkRepository;
use Illuminate\Http\Response;

class Send extends Service
{
    const CONTEXT           = "mengirim OTP";
    const MESSAGE_SUCCESS   = "berhasil mengirim OTP";
    const MESSAGE_ERROR     = "gagal mengirim OTP";

    public function __construct(protected int $phone)
    {
        $this->data = [
            'link'  => '',
            'qr'    => '',
        ];
    }

    static function formatPhone(string $phone) : string {
        return substr_replace($phone, '62', 0, 1);
    }

    function call(): ServiceResponse
    {
        try {

            $otp = OtpRepository::first(['phone' => $this->phone]);
            switch ($otp->status) {
                case Otp::STATUS_ACTIVE:
                    $this->data = [
                        'link'  => $otp->external_response['data']['verification']['waLink'],
                        'qr'    => $otp->external_response['data']['verification']['qrCode'],
                    ];
        
                    return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
                    break;
                case Otp::STATUS_VALID:
                    OtpRepository::delete($otp->id);
                    break;
            }

            $send_otp_body              = new Verification;
            $send_otp_body->userPhone   = self::formatPhone($this->phone);

            $send_otp = (new SendtalkRepository)->sendVerification($send_otp_body);
            $send_otp_result = $send_otp->json();

            if ($send_otp->failed() || $send_otp_result['status'] != Response::HTTP_OK || $send_otp_result['data']['success'] == false) {
                logger()->channel('sendtalk')->info("send verification", $send_otp_result);
                return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_GATEWAY);
            }

            OtpRepository::create([
                'phone'             => $this->phone,
                'status'            => Otp::STATUS_ACTIVE,
                'external_response' => $send_otp->body(),
            ]);

            $this->data = [
                'link'  => $send_otp_result['waLink'],
                'qr'    => $send_otp_result['qrCode'],
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);            
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
