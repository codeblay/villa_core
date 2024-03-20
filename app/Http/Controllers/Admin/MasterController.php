<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\BankRepository;
use App\Repositories\DestinationCategoryRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\FacilityRepository;
use App\Services\Bank\BankService;
use App\Services\Destination\DestinationService;
use App\Services\Facility\FacilityService;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    function facility()
    {
        $data['facilities'] = FacilityRepository::getWithTotalVilla();
        return view('pages.admin.master.facility', $data);
    }
    
    function facilityCreate(Request $request)
    {
        $service = FacilityService::create($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
    
    function destinationCategory()
    {
        $data['categories'] = DestinationCategoryRepository::getWithTotalDestination();
        return view('pages.admin.master.destination.category', $data);
    }

    function destinationCategoryCreate(Request $request)
    {
        $service = DestinationService::createCategory($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
    
    function destinationList()
    {
        $data['destinations'] = DestinationRepository::get();
        $data['categories'] = DestinationCategoryRepository::get();
        return view('pages.admin.master.destination.list', $data);
    }
    
    function destinationListDetail(int $id)
    {
        $data['destination'] = DestinationRepository::first(['id' => $id]);
        return view('pages.admin.master.destination.detail', $data);
    }

    function destinationListCreate(Request $request)
    {
        $service = DestinationService::create($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }


    function bank()
    {
        $data['banks'] = BankRepository::get();
        return view('pages.admin.master.bank', $data);
    }

    function bankUpdate(Request $request)
    {
        $service = BankService::update($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
}
