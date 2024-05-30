<?php

namespace App\Http\Controllers\API;

use App\Repositories\CityRepository;
use App\Repositories\FacilityRepository;
use App\Repositories\SellerRepository;
use App\Repositories\VillaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Select2Controller
{
    function villa(Request $request): JsonResponse
    {
        $data = VillaRepository::select2($request->keyword ?? '');
        return response()->json($data);
    }

    function location(Request $request): JsonResponse
    {
        $data = CityRepository::select2($request->keyword ?? '');
        return response()->json($data);
    }
    
    function facility(Request $request): JsonResponse
    {
        $data = FacilityRepository::select2($request->keyword ?? '');
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

    function facilityDetail(int $id): JsonResponse
    {
        $data = FacilityRepository::select2Single($id);
        return response()->json($data);
    }

    function investorVilla(Request $request, int $villa_id): JsonResponse
    {
        $data = SellerRepository::select2Investor($villa_id, $request->keyword ?? '');
        return response()->json($data);
    }
}
