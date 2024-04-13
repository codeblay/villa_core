<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\DestinationCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class UpdateCategory extends Service
{
    const CONTEXT           = "menyimpan kategori";
    const MESSAGE_SUCCESS   = "berhasil menyimpan kategori";
    const MESSAGE_ERROR     = "gagal menyimpan kategori";

    const RULES_VALIDATOR = [
        'name' => 'required|string|unique:destination_categories',
    ];

    public function __construct(protected Request $request, protected int $id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $category = DestinationCategoryRepository::first(['id' => $this->id]);
            if (!$category) return parent::error("data kategori tidak valid", Response::HTTP_BAD_REQUEST);
            
            if ($category->name == $this->request->name) goto SUCCESS;

            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            DestinationCategoryRepository::update($this->id, $validator->validated());

            SUCCESS:
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
