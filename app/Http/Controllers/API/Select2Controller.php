<?php

namespace App\Http\Controllers\API;

use App\Repositories\CityRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Select2Controller
{
    function location(Request $request): JsonResponse
    {
        $data = CityRepository::select2($request->keyword ?? '');
        return response()->json($data);
    }

    function seller(Request $request): JsonResponse
    {
        $data = SellerRepository::select2($request->keyword ?? '');
        return response()->json($data);
    }

    function locationDetail(int $id): JsonResponse
    {
        $data = CityRepository::select2Single($id);
        return response()->json($data);
    }

    function sellerDetail(int $id): JsonResponse
    {
        $data = SellerRepository::select2Single($id);
        return response()->json($data);
    }
}
