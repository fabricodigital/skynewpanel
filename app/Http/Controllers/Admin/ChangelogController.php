<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ChangelogController extends Controller
{
    public function index(){     
                
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.changelog.index');
    }
}
