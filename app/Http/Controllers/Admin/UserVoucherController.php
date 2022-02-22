<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UserVoucherController extends Controller
{
    public function search()
    {
        return view('admin.user-voucher.index');
    }


    public function searchClient(Request $request)
    {
         return $request->get('infocode');
    }
}
