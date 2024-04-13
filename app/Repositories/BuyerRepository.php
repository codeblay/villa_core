<?php

namespace App\Repositories;

use App\Interface\RepositoryApi;
use App\Models\Buyer;
use App\Models\DTO\SearchBuyer;
use App\MyConst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
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

    static function listForAdmin(int $cursor, SearchBuyer $param): LengthAwarePaginator
    {
        return Buyer::query()
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->withCount('transactionsSuccess')
            ->orderByDesc('email_verified_at')
            ->orderByDesc('transactions_success_count')
            ->paginate($cursor);
    }
}
