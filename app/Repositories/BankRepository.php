<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Bank;
use Illuminate\Database\Eloquent\Collection;

final class BankRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Bank::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Bank
    {
        return Bank::query()->where($conditions)->first();
    }

    static function create(array $data): ?Bank
    {
        return Bank::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Bank::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Bank::query()->where('id', $id)->delete();
    }
}
