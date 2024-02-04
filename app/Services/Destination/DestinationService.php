<?php

namespace App\Services\Destination;

use App\Models\DTO\ServiceResponse;
use App\Services\Destination\Action\CreateCategory;
use Illuminate\Http\Request;

final class DestinationService
{
    static function createCategory(Request $request): ServiceResponse
    {
        return (new CreateCategory($request))->call();
    }
}
