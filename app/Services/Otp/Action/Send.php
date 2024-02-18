<?php

namespace App\Services\Otp\Action;

use App\Base\Service;
use App\Models\DTO\Sendtalk\Message;
use App\Models\DTO\ServiceResponse;
use App\Models\Otp;
use App\Repositories\OtpRepository;
use App\Repositories\SendtalkRepository;
use Illuminate\Http\Response;

class Send extends Service
{
    const CONTEXT           = "send OTP";
    const MESSAGE_SUCCESS   = "success send OTP";
    const MESSAGE_ERROR     = "failed send OTP";

    public function __construct(protected int $phone)
    {
        $this->data['code'] = '';
    }

    static function formatPhone(string $phone) : string {
        return substr_replace($phone, '62', 0, 1);
    }

    function call(): ServiceResponse
    {
        try {

            $latest_otp = OtpRepository::latest(['phone' => $this->phone]);
            if ($latest_otp) {
                if ($latest_otp->is_expired) {
                    OtpRepository::update($latest_otp->id, ['status' => Otp::STATUS_EXPIRED]);
                }

                switch ($latest_otp->status) {
                    case Otp::STATUS_ACTIVE:
                        $otp_code = $latest_otp->code;
                        break;

                    case Otp::STATUS_EXPIRED:
                        $otp_code = Otp::generateOtp();
                        break;


                    case Otp::STATUS_VALID:
                        $otp_code = Otp::generateOtp();
                        break;
                    }
            } else {
                $otp_code = Otp::generateOtp();
            }

            $send_otp_body              = new Message;
            $send_otp_body->phone       = self::formatPhone($this->phone);
            $send_otp_body->messageType = $send_otp_body::TYPE_OTP;
            $send_otp_body->body        = $otp_code;

            $send_otp = (new SendtalkRepository)->sendMessage($send_otp_body);
            $send_otp_result = $send_otp->json();

            if ($send_otp->failed() || $send_otp_result['status'] != Response::HTTP_OK) {
                return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_GATEWAY);
            }

            OtpRepository::create([
                'phone'         => $this->phone,
                'code'          => $otp_code,
                'status'        => Otp::STATUS_ACTIVE,
                'expired_at'    => now()->addMinutes(10),
            ]);

            $this->data['code'] = $otp_code;

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);            
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
