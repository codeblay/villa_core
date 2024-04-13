<?php

namespace App\Services\Firebase\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Repositories\FirebaseRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Send extends Service
{
    const CONTEXT           = "mengirim notif fcm";
    const MESSAGE_SUCCESS   = "berhasil mengirim notif fcm";
    const MESSAGE_ERROR     = "gagal mengirim notif fcm";

    const RULES_VALIDATOR = [
        'title' => 'required|string',
        'body'  => 'required|string',
    ];

    public function __construct(protected Buyer|Seller $user, protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            
            if (empty($this->user->fcm_token)) {
                return parent::error("token user tidak tersedia");
            }

            (new FirebaseRepository)->send($this->user->fcm_token, $this->request->title, $this->request->body);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
