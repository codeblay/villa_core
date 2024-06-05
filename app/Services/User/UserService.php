<?php

namespace App\Services\User;

use App\Models\DTO\ServiceResponse;
use App\Models\User;
use App\Services\User\Action\ListForAdmin;
use App\Services\User\Action\ChangePassword;
use App\Services\User\Action\Store;
use Illuminate\Http\Request;

class UserService
{
    static function listForAdmin(Request $request): ServiceResponse
    {
        return (new ListForAdmin($request))->call();
    }

    static function store(Request $request): ServiceResponse
    {
        return (new Store($request))->call();
    }

    static function changePassword(Request $request, User $user): ServiceResponse
    {
        return (new ChangePassword($request, $user))->call();
    }
}
