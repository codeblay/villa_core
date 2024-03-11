<?php

namespace App\Repositories;

use App\Interface\RepositoryApi;
use App\Models\Buyer;
use App\MyConst;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;

final class BuyerRepository implements RepositoryApi
{
    static function get(array $conditions = []): Collection
    {
        return Buyer::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Buyer
    {
        return Buyer::query()->where($conditions)->first();
    }

    static function create(array $data): ?Buyer
    {
        return Buyer::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Buyer::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Buyer::query()->where('id', $id)->delete();
    }

    static function token(User $buyer): string
    {
        if (!($buyer instanceof Buyer)) return '';
        if ($buyer->tokens()->count() >= 3) $buyer->tokens()->delete();
        return $buyer->createToken(MyConst::USER_BUYER)->plainTextToken ?? '';
    }
}
