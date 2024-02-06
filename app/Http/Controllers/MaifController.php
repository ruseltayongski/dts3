<?php

namespace App\Http\Controllers;

use App\Tracking_Details;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MaifController extends Controller
{
    static function track($route_no)
    {
        $document = Tracking_Details::where('route_no',$route_no)
            ->orderBy('date_in','asc')
            ->get();
        Session::put('route_no', $route_no);
        return view('document.track_maif',['document' => $document]);
    }
}
