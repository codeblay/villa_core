<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

final class TransactionRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Transaction::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Transaction
    {
        return Transaction::query()->where($conditions)->first();
    }

    static function create(array $data): ?Transaction
    {
        return Transaction::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Transaction::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Transaction::query()->where('id', $id)->delete();
    }
}
