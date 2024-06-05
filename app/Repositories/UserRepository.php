<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\DTO\SearchAgent;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class UserRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return User::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?User
    {
        return User::query()->where($conditions)->first();
    }

    static function create(array $data): ?User
    {
        return User::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return User::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return User::query()->where('id', $id)->delete();
    }

    static function listAgentAdmin(int $cursor, SearchAgent $param): LengthAwarePaginator
    {
        return User::query()->where('is_admin', false)
        ->when($param->name, function (Builder $query, string $name) {
            $query->where('name', 'LIKE', "%{$name}%");
        })
        ->paginate($cursor);
    }
}
