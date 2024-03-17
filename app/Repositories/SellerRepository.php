<?php

namespace App\Repositories;

use App\Interface\RepositoryApi;
use App\Models\Seller;
use App\MyConst;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User;

final class SellerRepository implements RepositoryApi
{
    static function get(array $conditions = []): Collection
    {
        return Seller::query()->where($conditions)->get();
    }

    static function getWithTotalVilla(array $conditions = []): Collection
    {
        return Seller::query()->where($conditions)->withCount('villas')->get();
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
}
