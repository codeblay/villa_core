<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\DestinationCategoryRepository;
use Illuminate\Http\Response;

final class DeleteCategory extends Service
{
    const CONTEXT           = "menghapus kategori";
    const MESSAGE_SUCCESS   = "berhasil menghapus kategori";
    const MESSAGE_ERROR     = "gagal menghapus kategori";

    public function __construct(protected int $id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $category = DestinationCategoryRepository::first(['id' => $this->id]);
            if (!$category) return parent::error("data kategori tidak valid", Response::HTTP_BAD_REQUEST);

            if (count($category->destinations) > 0) return parent::error("kategori telah digunakan oleh beberapa destinasi", Response::HTTP_BAD_REQUEST);

            DestinationCategoryRepository::delete($this->id);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
