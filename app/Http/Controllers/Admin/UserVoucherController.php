<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\UserVoucher;
use Illuminate\Support\Facades\DB;


class UserVoucherController extends Controller
{
    public function search()
    {
        return view('admin.user-voucher.index');
    }


    public function searchClient(Request $request)
    {
        $search = $request->get('infocode');
        if(!empty($search)) {
            $uservouch =  UserVoucher::where('idSky', '=', $search)->orWhere('codicefiscale', '=', $search)->first();

            $getPromotions = DB::connection('solopertedev')->select(
                "SELECT * FROM elencopromozioni"
            );


            return $getPromotions;

        }
    }
}
