<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    function index()
    {
        $data['sellers'] = SellerRepository::getWithTotalVilla();
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
