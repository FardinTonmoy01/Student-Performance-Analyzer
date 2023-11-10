<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\InstituteInfo;

class HomeController extends Controller
{
    public function index()
    {
        $data = InstituteInfo::where('status', 1)
        ->select('inst_name', 'logo', 'sorting_order', 'address')
        ->get();

        return view('frontend.home.home', [ 'data' => $data ]);
    }
}
