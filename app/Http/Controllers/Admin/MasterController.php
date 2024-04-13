<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchDestination;
use App\MyConst;
use App\Repositories\BankRepository;
use App\Repositories\DestinationCategoryRepository;
use App\Repositories\DestinationRepository;
use App\Repositories\FacilityRepository;
use App\Services\Bank\BankService;
use App\Services\Banner\BannerService;
use App\Services\Destination\DestinationService;
use App\Services\Document\DocumentService;
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
    
    function facilityUpdate(Request $request, int $id)
    {
        $service = FacilityService::update($request, $id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
    
    function facilityDelete(int $id)
    {
        $service = FacilityService::delete($id);
        
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
    

    function destinationCategoryUpdate(Request $request, int $id)
    {
        $service = DestinationService::updateCategory($request, $id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
    

    function destinationCategoryDelete(int $id)
    {
        $service = DestinationService::deleteCategory($id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
    
    function destinationList(Request $request)
    {
        $param              = new SearchDestination;
        $param->name        = $request->name;
        $param->city_id     = $request->city_id;
        $param->category_id = $request->category_id;

        $data['destinations'] = DestinationRepository::listForAdmin(20, $param);
        $data['categories'] = DestinationCategoryRepository::get();
        return view('pages.admin.master.destination.list', $data);
    }
    
    function destinationListDetail(int $id)
    {
        $data['destination'] = DestinationRepository::first(['id' => $id]);
        return view('pages.admin.master.destination.detail', $data);
    }
    
    function destinationListDelete(int $id)
    {
        $service = DestinationService::delete($id);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function destinationListEdit(int $id)
    {
        $data['destination'] = DestinationRepository::first(['id' => $id]);
        $data['categories'] = DestinationCategoryRepository::get();
        return view('pages.admin.master.destination.edit', $data);
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
    
    function destinationListUpdate(Request $request)
    {
        $service = DestinationService::edit($request);
        
        return redirect()->route('admin.master.destination.list')->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function bank()
    {
        $data['banks'] = BankRepository::get();
        return view('pages.admin.master.payment', $data);
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

    function document()
    {
        $data['document']       = "pdf/" . MyConst::DOCUMENT_VERIFICATION_NAME;
        $data['document_exist'] = file_exists(public_path($data['document']));

        return view('pages.admin.master.document', $data);
    }

    function documentUpdate(Request $request)
    {
        $service = DocumentService::upload($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function banner()
    {
        $data['banner']       = "banner/" . MyConst::BANNER_NAME;
        $data['banner_exist'] = file_exists(public_path($data['banner']));

        return view('pages.admin.master.banner', $data);
    }

    function bannerUpdate(Request $request)
    {
        $service = BannerService::upload($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function bannerDelete(Request $request)
    {
        $service = BannerService::delete($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
}
