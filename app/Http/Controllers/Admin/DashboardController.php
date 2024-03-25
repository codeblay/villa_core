<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;

class DashboardController extends Controller
{
    function index()
    {
        $data = DashboardService::admin();
        return view('pages.admin.dashboard', $data->data);
    }
}
