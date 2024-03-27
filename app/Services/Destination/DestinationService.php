<?php

namespace App\Services\Destination;

use App\Models\DTO\ServiceResponse;
use App\Services\Destination\Action\Create;
use App\Services\Destination\Action\CreateCategory;
use App\Services\Destination\Action\UpdateCategory;
use App\Services\Destination\Action\DeleteCategory;
use App\Services\Destination\Action\Detail;
use App\Services\Destination\Action\ListByCategory;
use App\Services\Destination\Action\Edit;
use App\Services\Destination\Action\Delete;
use Illuminate\Http\Request;

final class DestinationService
{
    static function createCategory(Request $request): ServiceResponse
    {
        return (new CreateCategory($request))->call();
    }

    static function updateCategory(Request $request, int $id): ServiceResponse
    {
        return (new UpdateCategory($request, $id))->call();
    }

    static function deleteCategory(int $id): ServiceResponse
    {
        return (new DeleteCategory($id))->call();
    }

    static function detail(int $destination_id): ServiceResponse
    {
        return (new Detail($destination_id))->call();
    }

    static function listByCategory(int $category_id): ServiceResponse
    {
        return (new ListByCategory($category_id))->call();
    }

    static function create(Request $request): ServiceResponse
    {
        return (new Create($request))->call();
    }

    static function edit(Request $request): ServiceResponse
    {
        return (new Edit($request))->call();
    }

    static function delete(int $id): ServiceResponse
    {
        return (new Delete($id))->call();
    }
}
