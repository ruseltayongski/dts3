<?php

namespace App\Http\Controllers;

use App\Tracking_Details;
use App\Tracking_Releasev2;
use Illuminate\Http\Request;

use App\Http\Requests;

class ReportController extends Controller
{
    function report(){
        $year = date('Y');
        $start = $year.'-01-01 00:00:00';
        $end = $year.'-01-31 23:59:59';


        $divison = Division::orderBy('description','asc')->get();
        return view('report.documents',['division'=>$divison]);
    }

    function countReleaseTo($year){
        $released_to = Tracking_Releasev2::where('released_by',"=", 985452)
            ->get();
        $month="";
        $count="";

        return view('report.releasedDocuments',[
            'released_to' => $released_to,
            'year' => $year
        ]);
    }

}
