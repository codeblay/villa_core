<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\Destination;
use App\Models\DTO\ServiceResponse;
use App\Models\Facility;
use App\Models\Seller;
use App\Models\Villa;
use App\Models\VillaType;
use App\Repositories\DestinationCategoryRepository;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class Detail extends Service
{
    const CONTEXT           = "memuat villa";
    const MESSAGE_SUCCESS   = "berhasil memuat villa";
    const MESSAGE_ERROR     = "gagal memuat villa";

    public function __construct(protected int $villa_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            if (auth()?->user() instanceof Seller) {
                $villa = VillaRepository::detailForSeller($this->villa_id);
            } else {
                $villa = VillaRepository::detailForBuyer($this->villa_id);
            }
            
            if (!$villa) return parent::error('villa not found', Response::HTTP_BAD_REQUEST);

            $destination_categories = DestinationCategoryRepository::get();

            $this->data                             = self::mapVilla($villa);
            $this->data['destination_categories']   = self::mapDestination($destination_categories, $villa->city_id);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    static function mapVilla(Villa $villa): array
    {
        return [
            'id'            => $villa->id,
            'name'          => $villa->name,
            'city_id'       => $villa->city_id,
            'address'       => $villa->city->address,
            'description'   => $villa->description,
            'rating'        => $villa->rating,
            'can_book'      => $villa->is_publish == 1,
            'images'        => $villa->files->pluck('local_path')->toArray(),
            'facilities'    => $villa->villaTypes->map(function(VillaType $villa_type){
                return $villa_type->facilities->pluck('name')->toArray();
            })->flatten()->unique()->values()->toArray(),
            'units' => $villa->villaTypesPublish->map(function(VillaType $villa_type){
                return [
                    'id'    => $villa_type->id,
                    'name'  => $villa_type->name,
                    'price' => $villa_type->price,
                    'images'=> $villa_type->primaryImage->local_path,
                ];
            })->toArray(),
        ];
    }

    private static function mapDestination(Collection $destination_categories, int $city_id): array
    {
        foreach ($destination_categories as $destination_category) {
            $destination_category->load('destinations');
            $result[] = [
                'id'            => $destination_category->id,
                'name'          => $destination_category->name,
                'destinations'  => $destination_category->destinations->where('city_id', $city_id)->take(5)->map(function(Destination $destination){
                    return [
                        'id'    => $destination->id,
                        'name'  => $destination->name,
                        'image' => $destination->image_path,
                    ];
                })->toArray(),
            ];
        }

        return $result ?? [];
    }
}
