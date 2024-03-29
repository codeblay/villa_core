<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class CityRepository
{
    static function select2(string $keyword = ''): array
    {
        return City::query()
            ->where('name', 'LIKE', "%$keyword%")
            ->orWhereHas('province', function (Builder $query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%");
            })
            ->limit(5)
            ->get()
            ->map(function (City $city) {
                return [
                    'id'    => $city->id,
                    'text'  => $city->address,
                ];
            })
            ->toArray();
    }

    static function select2Single(int $id): array
    {
        $city = City::query()->find($id);
        return [
            'id'    => @$city->id,
            'text'  => @$city->address,
        ];
    }

    
    static function search(string $keyword = '', int $limit = 10): ?Collection
    {
        return City::query()
            ->where('name', 'LIKE', "%$keyword%")
            ->orWhereHas('province', function (Builder $query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%");
            })
            ->limit($limit)
            ->get();;
    }
}
