<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\DTO\SearchVilla;
use App\Models\File;
use App\Models\VillaInvestor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class VillaInvestorRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return VillaInvestor::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?VillaInvestor
    {
        return VillaInvestor::query()->where($conditions)->first();
    }

    static function create(array $data): ?VillaInvestor
    {
        return VillaInvestor::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return VillaInvestor::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return VillaInvestor::query()->where('id', $id)->delete();
    }

    static function cursorByVilla(int $villa_id, string $keyword, int $cursor): CursorPaginator
    {
        return VillaInvestor::query()
            ->with(['investor'])
            ->where('villa_id', $villa_id)
            ->when($keyword, function(Builder $query, $x){
                $query->whereHas('investor', function(Builder $query) use ($x){
                    $query->where('name', 'LIKE', "%$x%")
                    ->orWhere('email', 'LIKE', "%$x%");
                });
            })
            ->latest()
            ->cursorPaginate($cursor);
    }
}
