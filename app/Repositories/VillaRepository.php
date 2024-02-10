<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Villa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

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

    static function cursorBySeller(int $seller_id, int $cursor): CursorPaginator
    {
        return Villa::query()->with(['seller', 'city'])->where('seller_id', $seller_id)->cursorPaginate($cursor);
    }

    static function limit(int $limit): Collection
    {
        return Villa::query()->with(['city'])->where('is_publish', Villa::STATUS_PUBLISH)->limit($limit)->get();
    }

    static function detailForBuyer(int $id): ?Villa
    {
        return Villa::query()->with(['city', 'facilities'])->where('id', $id)->first();
    }
}
