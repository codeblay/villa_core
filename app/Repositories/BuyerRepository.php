<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Buyer;
use Illuminate\Database\Eloquent\Collection;

final class BuyerRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Buyer::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Buyer
    {
        return Buyer::query()->where($conditions)->first();
    }

    static function create(array $data): ?Buyer
    {
        return Buyer::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Buyer::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Buyer::query()->where('id', $id)->delete();
    }
}
