<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\Destination;
use App\Models\DTO\ServiceResponse;
use App\Repositories\DestinationRepository;
use Illuminate\Http\Response;

final class ListByCategory extends Service
{
    const CONTEXT           = "memuat destinasi";
    const MESSAGE_SUCCESS   = "berhasil memuat destinasi";
    const MESSAGE_ERROR     = "gagal memuat destinasi";

    private int $cursor = 10;

    public function __construct(protected int $category_id)
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
            $destinations = DestinationRepository::listByCategory($this->category_id, $this->cursor);

            $this->data['category']     = $destinations[0]->category->name ?? '';
            $this->data['destinations'] = $destinations->map(function (Destination $destination) {
                return self::mapDestination($destination);
            });
            $this->data['next']         = $destinations->nextCursor()?->encode();

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private static function mapDestination(Destination $destination): array
    {
        return [
            'name'          => $destination->name,
            'category'      => $destination->category->name,
            'address'       => $destination->city->address,
            'description'   => $destination->description,
        ];
    }
}
