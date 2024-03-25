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

    function verification(Request $request)
    {
        $param          = new SearchSeller;
        $param->name    = $request->name;

        $data['sellers'] = SellerRepository::needAccAdmin(10, $param);
        return view('pages.admin.verification.account', $data);
    }

    function verificationAccept(int $id)
    {
        $acc = SellerRepository::update($id, [
            'document_verified_at' => now()
        ]);
        
        return back()->with([
            'type'      => $acc ? 'success' : 'danger',
            'title'     => $acc ? 'Berhasil' : 'Gagal',
            'message'   => $acc ? 'Verifikasi berhasil' : 'verifikasi gagal, coba lagi',
        ]);
    }
}
