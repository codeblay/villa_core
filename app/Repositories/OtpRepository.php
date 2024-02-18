<?php

namespace App\Repositories;

use App\Interface\Repository;
use App\Models\Otp;
use Illuminate\Database\Eloquent\Collection;

final class OtpRepository implements Repository
{
    static function get(array $conditions = []): Collection
    {
        return Otp::query()->where($conditions)->get();
    }

    static function first(array $conditions = []): ?Otp
    {
        return Otp::query()->where($conditions)->first();
    }

    static function latest(array $conditions = []): ?Otp
    {
        return Otp::query()->where($conditions)->latest()->first();
    }

    static function create(array $data): ?Otp
    {
        return Otp::query()->create($data);
    }

    static function update(int $id, array $data): bool
    {
        return Otp::query()->where('id', $id)->update($data);
    }

    static function delete(int $id): bool
    {
        return Otp::query()->where('id', $id)->delete();
    }
}
