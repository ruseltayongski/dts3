<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

Use App\Tracking;
Use App\Tracking_Details;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }

    public function index($route_no) {
    	$track = Tracking_Details::where('tracking_details.route_no',$route_no)
            ->select("tracking_details.date_in","section.description")
            ->leftJoin("users","users.id","=","tracking_details.received_by")
            ->leftJoin("section","section.id","=","users.section")
            ->orderBy("tracking_details.id","desc")
            ->get();

    	return $track;
    }
}
