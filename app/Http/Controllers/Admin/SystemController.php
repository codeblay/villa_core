<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    function agentList(Request $request)
    {
        $data['users'] = UserService::listForAdmin($request)->data['users'];
        return view('pages.admin.system.agent', $data);
    }

    function agentStore(Request $request)
    {
        $service = UserService::store($request);
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }

    function changePassword()
    {
        return view('pages.admin.system.security');
    }

    function changePasswordStore(Request $request)
    {
        $service = UserService::changePassword($request, auth()->user());
        
        return back()->with([
            'type'      => $service->status ? 'success' : 'danger',
            'title'     => $service->status ? 'Berhasil' : 'Gagal',
            'message'   => ucfirst($service->message),
        ]);
    }
}
