<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\DTO\SearchFacility;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

final class FacilityRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Facility::query()->where($conditions)->get();
    }

    static function getWithTotalVilla(array $conditions = []): Collection
    {
        return Facility::query()->where($conditions)->withCount('villas')->get();
    }

    static function first(array $conditions = []): ?Facility
    {
        return Facility::query()->where($conditions)->first();
    }

    static function create(array $data): ?Facility
    {
        return Facility::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Facility::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Facility::query()->where('id', $id)->delete();
    }

    static function search(SearchFacility $searchFacility, int $limit = 10): Collection
    {
        return Facility::query()
            ->when(@$searchFacility->name, function(Builder $query, string $name){
                $query->where('name', "LIKE", "%{$name}%");
            })
            ->limit($limit)
            ->get();
    }
}
