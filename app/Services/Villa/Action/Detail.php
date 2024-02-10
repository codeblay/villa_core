<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Destination;
use App\Models\DTO\ServiceResponse;
use App\Models\Villa;
use App\Repositories\DestinationCategoryRepository;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class Detail extends Service
{
    const CONTEXT           = "load villa";
    const MESSAGE_SUCCESS   = "success load villa";
    const MESSAGE_ERROR     = "failed load villa";

    public function __construct(protected int $villa_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $villa = VillaRepository::detailForBuyer($this->villa_id);
            if (!$villa) return parent::error('villa not found', Response::HTTP_BAD_REQUEST);

            $destination_categories = DestinationCategoryRepository::get();

            $this->data                 = self::mapVilla($villa);
            $this->data['destination_categories']  = self::mapDestination($destination_categories);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private static function mapVilla(Villa $villa): array
    {
        return [
            'id'            => $villa->id,
            'name'          => $villa->name,
            'address'       => $villa->city->address,
            'price'         => $villa->price,
            'description'   => $villa->description,
            'can_book'      => $villa->can_book,
            'facilities'    => $villa->facilities->pluck('name')->toArray(),
        ];
    }

    private static function mapDestination(Collection $destination_categories): array
    {
        foreach ($destination_categories as $destination_category) {
            $destination_category->load('destinations');
            $result[] = [
                'id'            => $destination_category->id,
                'name'          => $destination_category->name,
                'destinations'  => $destination_category->destinations->take(5)->map(function(Destination $destination){
                    return [
                        'id'    => $destination->id,
                        'name'  => $destination->name,
                    ];
                })->toArray(),
            ];
        }

        return $result ?? [];
    }
}
