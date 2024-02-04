<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\DestinationCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class CreateCategory extends Service
{
    const CONTEXT           = "menyimpan kategori";
    const MESSAGE_SUCCESS   = "berhasil menyimpan kategori";
    const MESSAGE_ERROR     = "gagal menyimpan kategori";

    const RULES_VALIDATOR = [
        'name' => 'required|string|unique:destination_categories',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            DestinationCategoryRepository::create($validator->validated());

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
