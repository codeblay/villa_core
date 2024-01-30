<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface Repository
{
    static function get(array $conditions): Collection;
    static function first(array $condition): ?Model;
    static function create(array $data): ?Model;
    static function update(int $id, array $data): bool;
    static function delete(int $id): bool;
}
