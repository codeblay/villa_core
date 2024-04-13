<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchBuyer;
use App\Repositories\BuyerRepository;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    function index(Request $request)
    {
        $param          = new SearchBuyer;
        $param->name    = $request->name;
        
        $data['buyers'] = BuyerRepository::listForAdmin(20, $param);
        return view('pages.admin.user.buyer', $data);
    }
}
