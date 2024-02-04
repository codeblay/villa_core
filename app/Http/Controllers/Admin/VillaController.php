<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\VillaRepository;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    function index()
    {
        $data['villas'] = VillaRepository::get();
        return view('pages.admin.villa', $data);
    }
}
