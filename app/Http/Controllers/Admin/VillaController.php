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
        $param->is_publish  = $request->status;
        $param->rating      = $request->rating;

        $data['villas'] = VillaRepository::listForAdmin(20, $param);
        return view('pages.admin.villa.index', $data);
    }

    function highlight(Request $request)
    {
        $param              = new SearchVilla;
        $param->name        = $request->name;
        $param->city_id     = $request->city_id;
        $param->seller_id   = $request->seller_id;
        $param->is_publish  = $request->status;
        $param->rating      = $request->rating;

        $data['villas'] = VillaRepository::highlight(20, $param);
        return view('pages.admin.villa.higlight', $data);
    }

    function highlightUpdate(Request $request)
    {
        $highlight = VillaRepository::update($request->villa_id, [
            'promote' => $request->promote
        ]);

        return back()->with([
            'type'      => $highlight ? 'success' : 'danger',
            'title'     => $highlight ? 'Berhasil' : 'Gagal',
            'message'   => $highlight ? "Higlight villa berhasil" : "Higlight villa gagal",
        ]);
    }
    
    function detail(int $id)
    {
        $data['villa'] = VillaRepository::first(['id' => $id]);
        return view('pages.admin.villa.detail', $data);
    }
    
    function bypassRating(Request $request, int $id)
    {
        $rate = VillaRepository::update($id, [
            'bypass_rating' => $request->rate ?? 0
        ]);

        return back()->with([
            'type'      => $rate ? 'success' : 'danger',
            'title'     => $rate ? 'Berhasil' : 'Gagal',
            'message'   => $rate ? "Rate villa berhasil" : "Rate villa gagal",
        ]);
    }
}
