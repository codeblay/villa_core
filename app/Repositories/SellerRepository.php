<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

final class SellerRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Seller::query()->where($conditions)->get();
    }

    static function getWithTotalVilla(array $conditions = []): Collection
    {
        return Seller::query()->where($conditions)->withCount('villas')->get();
    }

    static function first(array $conditions = []): ?Seller
    {
        return Seller::query()->where($conditions)->first();
    }

    static function create(array $data): ?Seller
    {
        return Seller::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Seller::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Seller::query()->where('id', $id)->delete();
    }
}
