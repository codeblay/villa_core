<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\VillaSchedule;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;

final class VillaScheduleRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return VillaSchedule::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?VillaSchedule
    {
        return VillaSchedule::query()->where($conditions)->first();
    }

    static function create(array $data): ?VillaSchedule
    {
        return VillaSchedule::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return VillaSchedule::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return VillaSchedule::query()->where('id', $id)->delete();
    }

    static function deleteByTransaction(int $transaction_id): bool
    {
        return VillaSchedule::query()->where('transaction_id', $transaction_id)->delete();
    }

    static function schedule(int $villa_type_id, CarbonPeriod $period) : Collection
    {
        return VillaSchedule::query()->where('villa_type_id', $villa_type_id)->whereBetween('date', [$period->start, $period->end])->get();
    }
}
