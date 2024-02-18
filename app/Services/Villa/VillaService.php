<?php

namespace App\Services\Villa;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Services\Villa\Action\Booking;
use App\Services\Villa\Action\Create;
use App\Services\Villa\Action\Detail;
use App\Services\Villa\Action\ListAll;
use App\Services\Villa\Action\ListBySeller;
use App\Services\Villa\Action\Rate;
use App\Services\Villa\Action\Slider;
use Illuminate\Http\Request;

final class VillaService
{
    static function slider(): ServiceResponse
    {
        return (new Slider)->call();
    }
    
    static function detail(int $villa_id): ServiceResponse
    {
        return (new Detail($villa_id))->call();
    }

    static function create(Request $request, Seller $seller): ServiceResponse
    {
        return (new Create($request, $seller))->call();
    }

    static function edit(Request $request, Seller $seller): ServiceResponse
    {
        return (new Create($request, $seller))->call();
    }

    static function booking(Request $request, Buyer $buyer): ServiceResponse
    {
        return (new Booking($request, $buyer))->call();
    }

    static function ListAll(Request $request): ServiceResponse
    {
        return (new ListAll($request))->call();
    }

    static function ListBySeller(Seller $seller): ServiceResponse
    {
        return (new ListBySeller($seller))->call();
    }

    static function rate(Request $request, Buyer $buyer): ServiceResponse
    {
        return (new Rate($request, $buyer))->call();
    }
}
