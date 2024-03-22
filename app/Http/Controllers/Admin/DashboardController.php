<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    function index()
    {
        $data = DashboardRepository::dashboard();
        return view('pages.admin.dashboard', $data);
    }
}
