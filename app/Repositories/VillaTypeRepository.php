<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\VillaType;
use Illuminate\Database\Eloquent\Collection;

final class VillaTypeRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return VillaType::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?VillaType
    {
        return VillaType::query()->where($conditions)->first();
    }

    static function create(array $data): ?VillaType
    {
        return VillaType::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return VillaType::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return VillaType::query()->where('id', $id)->delete();
    }
}
