<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Mutation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class MutationRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Mutation::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Mutation
    {
        return Mutation::query()->where($conditions)->first();
    }

    static function create(array $data): ?Mutation
    {
        return Mutation::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Mutation::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Mutation::query()->where('id', $id)->delete();
    }

    static function listForAdmin(int $seller_id, int $paginate): LengthAwarePaginator
    {
        return Mutation::query()->where('seller_id', $seller_id)->oldest()->paginate($paginate);
    }

    static function activeBalanceSeller(int $seller_id): int
    {
        return Mutation::query()->where('seller_id', $seller_id)->sum('amount');
    }
}
