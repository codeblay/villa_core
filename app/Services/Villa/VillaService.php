<?php

namespace App\Services\Villa;

use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Models\User;
use App\Services\Villa\Action\AddInvestor;
use App\Services\Villa\Action\Booking;
use App\Services\Villa\Action\Create;
use App\Services\Villa\Action\Detail;
use App\Services\Villa\Action\ListAll;
use App\Services\Villa\Action\ListBySeller;
use App\Services\Villa\Action\Rate;
use App\Services\Villa\Action\Slider;
use App\Services\Villa\Action\Check;
use App\Services\Villa\Action\DeleteInvestor;
use App\Services\Villa\Action\Edit;
use App\Services\Villa\Action\Unit;
use App\Services\Villa\Action\UnitDetail;
use App\Services\Villa\Action\UnitDetailBuyer;
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

    static function create(Request $request, User $seller): ServiceResponse
    {
        return (new Create($request, $seller))->call();
    }

    static function edit(Request $request, Seller $seller): ServiceResponse
    {
        return (new Edit($request, $seller))->call();
    }

    static function booking(Request $request, Buyer $buyer): ServiceResponse
    {
        return (new Booking($request, $buyer))->call();
    }

    static function ListAll(Request $request): ServiceResponse
    {
        return (new ListAll($request))->call();
    }

    static function ListBySeller(Request $request, Seller $seller): ServiceResponse
    {
        return (new ListBySeller($request, $seller))->call();
    }

    static function rate(Request $request, Buyer $buyer): ServiceResponse
    {
        return (new Rate($request, $buyer))->call();
    }

    static function check(int $villa_type_id, Buyer $buyer): ServiceResponse
    {
        return (new Check($villa_type_id, $buyer))->call();
    }

    static function addInvestor(Request $request): ServiceResponse
    {
        return (new AddInvestor($request))->call();
    }

    static function deleteInvestor(int $id): ServiceResponse
    {
        return (new DeleteInvestor($id))->call();
    }

    static function unit(int $id): ServiceResponse
    {
        return (new Unit($id))->call();
    }

    static function unitDetail(int $id, $unit_detail): ServiceResponse
    {
        return (new UnitDetail($id, $unit_detail))->call();
    }

    static function unitDetailBuyer(int $id, $unit_detail): ServiceResponse
    {
        return (new UnitDetailBuyer($id, $unit_detail))->call();
    }
}
