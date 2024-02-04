<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Villa;
use Illuminate\Database\Eloquent\Collection;

final class VillaRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Villa::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Villa
    {
        return Villa::query()->where($conditions)->first();
    }

    static function create(array $data): ?Villa
    {
        return Villa::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Villa::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Villa::query()->where('id', $id)->delete();
    }
}
