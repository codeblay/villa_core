<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\VillaFacility;
use Illuminate\Database\Eloquent\Collection;

final class VillaFacilityRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return VillaFacility::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?VillaFacility
    {
        return VillaFacility::query()->where($conditions)->first();
    }

    static function create(array $data): ?VillaFacility
    {
        return VillaFacility::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return VillaFacility::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return VillaFacility::query()->where('id', $id)->delete();
    }
}
