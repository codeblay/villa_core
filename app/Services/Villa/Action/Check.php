<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaScheduleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Check extends Service
{
    const CONTEXT           = "rate villa";
    const MESSAGE_SUCCESS   = "berhasil rate villa";
    const MESSAGE_ERROR     = "gagal rate villa";

    public function __construct(protected int $villa_type_id, protected Buyer $buyer)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $booked_schedules = VillaScheduleRepository::get(['villa_type_id' => $this->villa_type_id])->pluck('date')->toArray();

            $this->data = [
                'booked' => $booked_schedules,
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
