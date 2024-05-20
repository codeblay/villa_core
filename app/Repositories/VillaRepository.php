<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\DTO\SearchVilla;
use App\Models\File;
use App\Models\Villa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class VillaRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Villa::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Villa
    {
        return Villa::query()->where($conditions)->first();
    }

    static function create(array $data): ?Villa
    {
        return Villa::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Villa::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Villa::query()->where('id', $id)->delete();
    }

    static function cursor(int $cursor, SearchVilla $param): CursorPaginator
    {
        return Villa::query()
            ->select(
                '*',
                DB::raw(
                'IF
                    (
                        villas.bypass_rating = 0, 
                        CEIL((SELECT SUM(rating) / COUNT(*) FROM villa_ratings WHERE villa_ratings.villa_id = villas.id)), 
                        villas.bypass_rating
                    )
                    AS rating'
                )
            )
            ->with(['city'])
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->when($param->order_by, function (Builder $query, string $column) use ($param) {
                if ($param->order_by == 'asc') {
                    $query->orderBy($column, $param->order_type);
                } else {
                    $query->orderByDesc($column, $param->order_type);
                }
            })
            ->orderBy('rating')
            ->where('is_publish', Villa::STATUS_PUBLISH)
            ->cursorPaginate($cursor);
    }

    static function listForAdmin(int $cursor, SearchVilla $param): LengthAwarePaginator
    {
        return Villa::query()
            ->select(
                '*',
                DB::raw(
                   'IF
                    (
                        villas.bypass_rating = 0, 
                        CEIL((SELECT SUM(rating) / COUNT(*) FROM villa_ratings WHERE villa_ratings.villa_id = villas.id)), 
                        villas.bypass_rating
                    )
                    AS rating'
                )
            )
            ->with(['city'])
            ->withCount(['investors'])
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->when(!is_null($param->is_publish), function (Builder $query) use ($param) {
                $query->where('is_publish', $param->is_publish);
            })
            ->when($param->rating, function (Builder $query, $rating) {
                $query->havingRaw("rating = $rating");
            })
            ->when($param->order_by, function (Builder $query, string $column) use ($param) {
                $query->orderBy($column, $param->order_type);
            })
            ->paginate($cursor);
    }

    static function highlight(int $cursor, SearchVilla $param): LengthAwarePaginator
    {
        return Villa::query()
            ->select(
                '*',
                DB::raw(
                   'IF
                    (
                        villas.bypass_rating = 0, 
                        CEIL((SELECT SUM(rating) / COUNT(*) FROM villa_ratings WHERE villa_ratings.villa_id = villas.id)), 
                        villas.bypass_rating
                    )
                    AS rating'
                )
            )
            ->with(['city'])
            ->where('promote', true)
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->when(!is_null($param->is_publish), function (Builder $query) use ($param) {
                $query->where('is_publish', $param->is_publish);
            })
            ->when($param->rating, function (Builder $query, $rating) {
                $query->havingRaw("rating = $rating");
            })
            ->when($param->order_by, function (Builder $query, string $column) use ($param) {
                $query->orderBy($column, $param->order_type);
            })
            ->paginate($cursor);
    }

    static function cursorBySeller(int $seller_id, SearchVilla $param, int $cursor): CursorPaginator
    {
        return Villa::query()
            ->whereHas('investors', function(Builder $query) use ($seller_id){
                $query->where('investor_id', $seller_id);
            })
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->latest()
            ->cursorPaginate($cursor);
    }

    static function limit(int $limit): Collection
    {
        return Villa::query()->with(['city'])->where('is_publish', Villa::STATUS_PUBLISH)->limit($limit)->get();
    }

    static function slider(int $limit): Collection
    {
        return Villa::query()->with(['city'])->where('promote', true)->limit($limit)->get();
    }

    static function detailForBuyer(int $id): ?Villa
    {
        return Villa::query()->with(['villaTypes', 'city', 'facilities'])->where('is_publish', Villa::STATUS_PUBLISH)->where('id', $id)->first();
    }

    static function detailForSeller(int $id): ?Villa
    {
        return Villa::query()->with(['villaTypes', 'city', 'facilities'])->where('id', $id)->first();
    }

    static function select2(string $keyword = ''): array
    {
        return Villa::query()
            ->where('name', 'LIKE', "%$keyword%")
            ->where('promote', false)
            ->where('is_publish', true)
            ->limit(5)
            ->get()
            ->map(function (Villa $villa) {
                return [
                    'id'    => $villa->id,
                    'text'  => $villa->name,
                ];
            })
            ->toArray();
    }
}
