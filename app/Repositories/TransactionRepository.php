<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\DTO\SearchTransaction;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
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

    static function listForBuyer(int $buyer_id, SearchTransaction $param, int $cursor): CursorPaginator
    {
        return Transaction::query()
            ->with(['villa'])
            ->where('buyer_id', $buyer_id)
            ->when(!is_null($param->status), function(Builder $query) use ($param){
                $query->where('status', $param->status);
            })
            ->when($param->code, function (Builder $query, string $x) {
                $query->where('code', $x);
            })
            ->when($param->status, function (Builder $query, string $x) {
                $query->where('status', $x);
            })
            ->when($param->start_date, function (Builder $query) use ($param) {
                $query->whereBetween('created_at', [$param->start_date, $param->end_date]);
            })
            ->latest()
            ->cursorPaginate($cursor);
    }

    static function listForSeller(int $seller_id, SearchTransaction $param, int $cursor): CursorPaginator
    {
        return Transaction::query()
            ->whereHas('villa', function(Builder $query) use ($seller_id, $param){
                $query->when($param->villa_id, function (Builder $query, string $x){
                    $query->where('uuid', $x);
                })->where('seller_id', $seller_id);
            })
            ->when($param->code, function (Builder $query, string $x) {
                $query->where('code', $x);
            })
            ->when($param->status, function (Builder $query, string $x) {
                $query->where('status', $x);
            })
            ->when(!is_null($param->status), function(Builder $query) use ($param){
                $query->where('status', $param->status);
            })
            ->when($param->start_date, function (Builder $query) use ($param) {
                $query->whereBetween('created_at', [$param->start_date, $param->end_date]);
            })
            ->latest()
            ->cursorPaginate($cursor);
    }

    static function listForAdmin(int $cursor, SearchTransaction $param): LengthAwarePaginator
    {
        return Transaction::query()
            ->when($param->code, function (Builder $query, string $code) {
                $query->where('code', 'LIKE', "%{$code}%");
            })
            ->with(['villa', 'buyer'])
            ->paginate($cursor);
    }

    static function totalThisMonthBySeller(int $seller_id) : int {
        return Transaction::query()
            ->whereHas('villa', function(Builder $query) use ($seller_id){
                $query->where('seller_id', $seller_id);
            })
            ->where('status', Transaction::STATUS_SUCCESS)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    static function valueThisMonthBySeller(int $seller_id) : int {
        return Transaction::query()
            ->whereHas('villa', function(Builder $query) use ($seller_id){
                $query->where('seller_id', $seller_id);
            })
            ->where('status', Transaction::STATUS_SUCCESS)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
    }

    static function needConfirmation(int $seller_id): Collection
    {
        return Transaction::query()
            ->with('villa')
            ->whereHas('villa', function(Builder $query) use ($seller_id){
                $query->where('seller_id', $seller_id);
            })
            ->where('status', Transaction::STATUS_PENDING)
            ->limit(3)
            ->get();
    }

    static function count(array $conditions = []): int
    {
        return Transaction::query()->where($conditions)->count();
    }
}
