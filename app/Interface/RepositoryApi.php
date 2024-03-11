<?php

namespace App\Interface;

use Illuminate\Foundation\Auth\User;

interface RepositoryApi extends Repository
{
    static function token(User $user): string;
}
