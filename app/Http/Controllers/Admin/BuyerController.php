<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\BuyerRepository;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    function index()
    {
        $data['buyers'] = BuyerRepository::get();
        return view('pages.admin.user.buyer', $data);
    }
}
