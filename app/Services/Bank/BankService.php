<?php

namespace App\Services\Bank;

use App\Models\DTO\ServiceResponse;
use App\Services\Bank\Action\Update;
use Illuminate\Http\Request;

class BankService
{
    static function update(Request $request): ServiceResponse
    {
        return (new Update($request))->call();
    }
}
