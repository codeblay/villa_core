<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\Destination;
use App\Models\DTO\ServiceResponse;
use App\Repositories\DestinationRepository;
use Illuminate\Http\Response;

final class Detail extends Service
{
    const CONTEXT           = "memuat destinasi";
    const MESSAGE_SUCCESS   = "berhasil memuat destinasi";
    const MESSAGE_ERROR     = "gagal memuat destinasi";

    public function __construct(protected int $destination_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $destination = DestinationRepository::firstWithRelation(['id' => $this->destination_id], ['city', 'category']);
            if (!$destination) parent::error('destination tidak ditemukan', Response::HTTP_BAD_REQUEST);

            $this->data = self::mapResult($destination);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private static function mapResult(Destination $destination): array
    {
        return [
            'name'          => $destination->name,
            'category'      => $destination->category->name,
            'address'       => $destination->city->address,
            'description'   => $destination->description,
        ];
    }
}
