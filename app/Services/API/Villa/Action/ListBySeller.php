<?php

namespace App\Services\API\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;

final class ListBySeller extends Service
{
    const CONTEXT           = "load villa";
    const MESSAGE_SUCCESS   = "success load villa";
    const MESSAGE_ERROR     = "failed load villa";

    private int $cursor = 10;

    function __construct(protected int $seller_id)
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
            $villa = VillaRepository::cursorBySeller($this->seller_id, $this->cursor);

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
                'seller'        => $villa->seller->name,
                'address'       => $villa->city->address,
                'price'         => $villa->price,
                'description'   => $villa->description,
                'is_publish'    => (bool) $villa->is_publish,
                'is_available'  => (bool) $villa->is_available,
            ];
        }
        return $result ?? [];
    }
}
