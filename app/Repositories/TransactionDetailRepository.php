<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Collection;

final class TransactionDetailRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return TransactionDetail::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?TransactionDetail
    {
        return TransactionDetail::query()->where($conditions)->first();
    }

    static function create(array $data): ?TransactionDetail
    {
        return TransactionDetail::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return TransactionDetail::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return TransactionDetail::query()->where('id', $id)->delete();
    }
}
