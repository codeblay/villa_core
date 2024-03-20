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
            ->with(['city'])
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->when($param->order_by, function (Builder $query, string $column) use ($param) {
                $query->orderBy($column, $param->order_type);
            })
            ->where('is_publish', Villa::STATUS_PUBLISH)
            ->cursorPaginate($cursor);
    }

    static function listForAdmin(int $cursor, SearchVilla $param): LengthAwarePaginator
    {
        return Villa::query()
            ->with(['city'])
            ->when($param->name, function (Builder $query, string $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($param->city_id, function (Builder $query, int $city_id) {
                $query->where('city_id', $city_id);
            })
            ->when($param->order_by, function (Builder $query, string $column) use ($param) {
                $query->orderBy($column, $param->order_type);
            })
            ->paginate($cursor);
    }

    static function cursorBySeller(int $seller_id, int $cursor): CursorPaginator
    {
        return Villa::query()->with(['seller', 'city'])->where('seller_id', $seller_id)->cursorPaginate($cursor);
    }

    static function limit(int $limit): Collection
    {
        return Villa::query()->with(['city'])->where('is_publish', Villa::STATUS_PUBLISH)->limit($limit)->get();
    }

    static function detailForBuyer(int $id): ?Villa
    {
        return Villa::query()->with(['city', 'facilities'])->where('is_publish', Villa::STATUS_PUBLISH)->where('id', $id)->first();
    }

    static function addImages(Villa $villa, UploadedFile $file): bool
    {
        $_file       = new File;
        $_file->path = "villa/" . $file->store(options: 'villa');
        $_file->type = File::TYPE_IMAGE;

        return $villa->files()->save($_file) instanceof File;
    }
}
