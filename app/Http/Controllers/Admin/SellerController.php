<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchSeller;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    function index(Request $request)
    {
        $param          = new SearchSeller;
        $param->name    = $request->name;
        
        $data['sellers'] = SellerRepository::listForAdmin(20, $param);
        return view('pages.admin.user.seller', $data);
    }

    function verification()
    {
        $data['sellers'] = SellerRepository::get([
            'document_verified_at' => null,
        ]);
        return view('pages.admin.verification.account', $data);
    }
}
