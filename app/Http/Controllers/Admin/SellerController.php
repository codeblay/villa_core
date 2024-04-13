<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchSeller;
use App\Repositories\SellerRepository;
use App\Services\Verification\VerificationService;
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
        $service = VerificationService::accept($id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function verificationDeny(int $id)
    {
        $service = VerificationService::deny($id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
}
