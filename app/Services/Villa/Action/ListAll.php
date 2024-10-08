<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\SearchVilla;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ListAll extends Service
{
    const CONTEXT           = "memuat villa";
    const MESSAGE_SUCCESS   = "berhasil memuat villa";
    const MESSAGE_ERROR     = "gagal memuat villa";

    private int $cursor = 10;

    function __construct(protected Request $request)
    {
    }

    function setCursor(int $cursor): self
    {
        $this->cursor = $cursor;
        return $this;
    }

    function call(): ServiceResponse
    {
        try {
            $param              = new SearchVilla;
            $param->name        = $this->request->name;
            $param->city_id     = $this->request->city_id;
            $param->order_by    = $this->request->order_by ?? 'rating';
            $param->order_type  = $this->request->order_type ?? 'desc';

            $villa = VillaRepository::cursor($this->cursor, $param);

            $this->data = [
                'result'    => self::mapResult($villa->items()),
                'next'      => $villa->nextCursor()?->encode(),
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    static function mapResult(array $data): array
    {
        foreach ($data as $villa) {
            $result[] = [
                'id'            => $villa->id,
                'name'          => $villa->name,
                'address'       => $villa->city->address,
                'price'         => $villa->price,
                'description'   => $villa->description,
                'rating'        => $villa->rating,
                'is_publish'    => (bool) $villa->is_publish,
                'image_url'     => $villa->primaryImage->local_path,
            ];
        }
        return $result ?? [];
    }
}
