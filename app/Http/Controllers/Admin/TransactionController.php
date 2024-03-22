<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DTO\SearchTransaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    function rent(Request $request)
    {
        $param          = new SearchTransaction;
        $param->code    = $request->code;

        $data['transactions'] = TransactionRepository::listForAdmin(20, $param);
        return view('pages.admin.transaction.rent', $data);
    }
}
