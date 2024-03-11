<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\VillaRating;
use Illuminate\Database\Eloquent\Collection;

final class VillaRatingRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return VillaRating::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?VillaRating
    {
        return VillaRating::query()->where($conditions)->first();
    }

    static function create(array $data): ?VillaRating
    {
        return VillaRating::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return VillaRating::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return VillaRating::query()->where('id', $id)->delete();
    }
}
