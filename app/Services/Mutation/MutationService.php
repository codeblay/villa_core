<?php

namespace App\Services\Mutation;

use App\Models\DTO\ServiceResponse;
use App\Services\Mutation\Action\ListForAdmin;
use App\Services\Mutation\Action\Store;
use Illuminate\Http\Request;

class MutationService
{
    static function listForAdmin(int $seller_id): ServiceResponse
    {
        return (new ListForAdmin($seller_id))->call();
    }

    static function store(Request $request, int $seller_id): ServiceResponse
    {
        return (new Store($request, $seller_id))->call();
    }
}
