<?php

namespace App\Services\Facility;

use App\Models\DTO\ServiceResponse;
use App\Services\Facility\Action\Create;
use App\Services\Facility\Action\Dropdown;
use App\Services\Facility\Action\Update;
use App\Services\Facility\Action\Delete;
use Illuminate\Http\Request;

final class FacilityService
{
    static function create(Request $request): ServiceResponse
    {
        return (new Create($request))->call();
    }

    static function update(Request $request, int $id): ServiceResponse
    {
        return (new Update($request, $id))->call();
    }

    static function delete(int $id): ServiceResponse
    {
        return (new Delete($id))->call();
    }

    static function dropdown(Request $request): ServiceResponse
    {
        return (new Dropdown($request))->call();
    }
}
