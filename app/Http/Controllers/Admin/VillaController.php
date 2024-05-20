<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchVilla;
use App\Repositories\VillaInvestorRepository;
use App\Repositories\VillaRepository;
use App\Services\Villa\VillaService;
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

    function create(Request $request)
    {
        return view('pages.admin.villa.create');
    }

    function investor(Request $request, int $id)
    {
        $data['items']      = VillaInvestorRepository::cursorByVilla($id, $request->keyword ?? '', 20);
        $data['villa_id']   = $id;
        return view('pages.admin.villa.investor', $data);
    }

    function store(Request $request)
    {
        $service = VillaService::create($request, auth()->user());
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function investorAdd(Request $request)
    {
        $service = VillaService::addInvestor($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function investorDelete(Request $request)
    {
        $service = VillaService::deleteInvestor($request->id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
}
