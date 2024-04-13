<?php

namespace App\Services\Facility\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\FacilityRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Update extends Service
{
    const CONTEXT           = "menyimpan fasilitas";
    const MESSAGE_SUCCESS   = "berhasil menyimpan fasilitas";
    const MESSAGE_ERROR     = "gagal menyimpan fasilitas";

    const RULES_VALIDATOR = [
        'name' => 'required|string|unique:facilities',
    ];

    public function __construct(protected Request $request, protected int $id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $facility = FacilityRepository::first(['id' => $this->id]);
            if (!$facility) return parent::error("data fasilitas tidak valid", Response::HTTP_BAD_REQUEST);
            
            if ($facility->name == $this->request->name) goto SUCCESS;

            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            FacilityRepository::update($this->id, $validator->validated());

            SUCCESS:
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
