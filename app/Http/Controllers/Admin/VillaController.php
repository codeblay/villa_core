<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchVilla;
use App\Repositories\VillaRepository;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    function index(Request $request)
    {
        $param              = new SearchVilla;
        $param->name        = $request->name;
        $param->city_id     = $request->city_id;
        $param->seller_id   = $request->seller_id;

        $data['villas'] = VillaRepository::listForAdmin(10, $param);
        return view('pages.admin.villa.index', $data);
    }
    
    function detail(int $id)
    {
        $data['villa'] = VillaRepository::first(['id' => $id]);
        return view('pages.admin.villa.detail', $data);
    }
}
