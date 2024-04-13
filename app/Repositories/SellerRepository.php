<?php

namespace App\Repositories;

use App\Interface\RepositoryApi;
use App\Models\DTO\SearchSeller;
use App\Models\Seller;
use App\MyConst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;

final class SellerRepository implements RepositoryApi
{
    static function get(array $conditions = []): Collection
    {
        return Seller::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Seller
    {
        return Seller::query()->where($conditions)->first();
    }

    static function create(array $data): ?Seller
    {
        return Seller::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Seller::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Seller::query()->where('id', $id)->delete();
    }

    static function token(User $seller): string
    {
        if (!($seller instanceof Seller)) return '';
        if ($seller->tokens()->count() >= 3) $seller->tokens()->delete();
        return $seller->createToken(MyConst::USER_SELLER)->plainTextToken ?? '';
    }

    static function select2(string $keyword = ''): array
    {
        return Seller::query()
            ->where('name', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "$keyword%")
            ->limit(5)
            ->get()
            ->map(function(Seller $city){
                return [
                    'id'    => $city->id,
                    'text'  => $city->name,
                ];
            })
            ->toArray();
    }

    static function select2Single(int $id): array
    {
        $seller = Seller::query()->find($id);
        return [
            'id'    => @$seller->id,
            'text'  => @$seller->name,
        ];
    }

    static function listForAdmin(int $paginate, SearchSeller $param): LengthAwarePaginator
    {
        return Seller::query()
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->withCount('villas')
            ->orderByDesc('villas_count')
            ->paginate($paginate);
    }

    static function needAccAdmin(int $paginate, SearchSeller $param): LengthAwarePaginator
    {
        return Seller::query()
            ->whereNotNull('email_verified_at')
            ->whereNull('document_verified_at')
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->latest()
            ->paginate($paginate);
    }
}
