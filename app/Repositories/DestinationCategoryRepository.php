<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\DestinationCategory;
use Illuminate\Database\Eloquent\Collection;

final class DestinationCategoryRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return DestinationCategory::query()->where($conditions)->get();
    }

    static function getWithTotalDestination(array $conditions = []): Collection
    {
        return DestinationCategory::query()
            ->where($conditions)
            ->withCount('destinations')
            ->orderByDesc('destinations_count')
            ->get();
    }

    static function first(array $conditions = []): ?DestinationCategory
    {
        return DestinationCategory::query()->where($conditions)->first();
    }

    static function create(array $data): ?DestinationCategory
    {
        return DestinationCategory::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return DestinationCategory::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return DestinationCategory::query()->where('id', $id)->delete();
    }
}
