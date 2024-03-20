<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Destination;
use App\Models\DTO\SearchDestination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

final class DestinationRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Destination::query()->where($conditions)->get();
    }

    static function listByCategory(int $category_id, int $cursor): CursorPaginator
    {
        return Destination::query()->with('category')->where('destination_category_id', $category_id)->cursorPaginate($cursor);
    }

    static function first(array $conditions = []): ?Destination
    {
        return Destination::query()->where($conditions)->first();
    }

    static function firstWithRelation(array $conditions = [], array $relations = []): ?Destination
    {
        return Destination::query()->with($relations)->where($conditions)->first();
    }

    static function create(array $data): ?Destination
    {
        return Destination::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Destination::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Destination::query()->where('id', $id)->delete();
    }

    static function listForAdmin(int $cursor, SearchDestination $param): LengthAwarePaginator
    {
        return Destination::query()
            ->with(['city', 'category'])
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->when($param->category_id, function (Builder $query, int $category_id) {
                $query->where('destination_category_id', $category_id);
            })
            ->paginate($cursor);
    }
}
