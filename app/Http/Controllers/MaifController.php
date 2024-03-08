<?php

namespace App\Http\Controllers;

use App\Tracking_Details;
use App\Section;
use App\Tracking;
use App\Users;
use App\Tracking_Releasev2;
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

    static function trackPO($route_no)
    {
        $document = Tracking_Details::where('route_no',$route_no)
            ->orderBy('date_in','asc')
            ->get();
        Session::put('route_no', $route_no);
        return view('document.track_po',['document' => $document]);
    }

    static function dv_no($dv_no, $route_no, $user)
    {
        $user = Users::where('username', $user)->first();
        if (isset($dv_no)) {
            Tracking::where('route_no', "=", $route_no)->update([
                'dv_no' => $dv_no
            ]);

            $tracking_details = Tracking_Details::where('route_no', $route_no)
                ->orderBy('date_in', 'desc')
                ->first();
            // return $tracking_details;
            if ($tracking_details->code == "temp;5") {
                $tracking_details->code = "accept;5";
                $tracking_details->received_by = $user->id;
                $tracking_details->save();

                $tracking_release1 = Tracking_Releasev2::where('route_no', $route_no)
                	->first();
                if($tracking_release1 != null){
  					$tracking_release1->status ="accept";
  	               	$tracking_release1->save();
                }
            }

            $doc_id = Tracking_Details::where('route_no', $route_no)
                ->orderBy('id', 'desc')
                ->first()
                ->id;    

             $release_to_datein = date('Y-m-d H:i:s');

            if ($doc_id != 0) {
                $table = Tracking_Details::where('id', $doc_id)->orderBy('id', 'DESC');
                $code = isset($table->first()->code) ? $table->first()->code : null;

                $tracking_release = new Tracking_Releasev2();
                $tracking_release->released_by = $user->id;
                $tracking_release->released_section_to = 6;
                $tracking_release->released_date = $release_to_datein;
                $tracking_release->remarks = $dv_no;
                $tracking_release->document_id = $table->first()->id;
                $tracking_release->route_no = $route_no;
                $tracking_release->status = "waiting";
                $tracking_release->save();

                $update = array(
                    'code' => null
                );

                $table->update($update);
                $tmp = explode(';', $code);
                $code = $tmp[0];
                if ($code == 'return') {
                    $table->delete();
                }
            } else {
                $tracking_details_info = Tracking_Details::where('route_no', $route_no)
                    ->orderBy('id', 'desc')
                    ->first();
                $tracking_details_id = $tracking_details_info->id;
                $update = array(
                    'code' => null
                );
                $table = Tracking_Details::where('id', $tracking_details_id);
                $table->update($update);
            }		

            $q = new Tracking_Details();
            $q->route_no = $route_no;
            $q->date_in = $release_to_datein;
            $q->action = $dv_no;
            $q->delivered_by = $user->id;
            $q->code = 'temp;' . 6;
            $q->save();

            Session::put("releaseAdded", [
                "route_no" => $route_no,
                "section_released_to_id" => 6,
                "user_released_name" => $user->fname . ' ' . $user->lname,
                "section_released_by_id" => $user->section,
                "section_released_by_name" => Section::find(6)->description,
                "remarks" => $dv_no,
                "status" => "released"
            ]);

            return 0;
        }
    }

    static function ors_no($ors_no, $route_no, $user)
    {
        $user = Users::where('username', $user)->first();
        if (isset($ors_no)) {
            Tracking::where('route_no', "=", $route_no)->update([
                'ors_no' => $ors_no
            ]);

            $tracking_details = Tracking_Details::where('route_no', $route_no)
                ->orderBy('date_in', 'desc')
                ->first();

            if ($tracking_details->code == "temp;6") {
                $tracking_details->code = "accept;6";
                $tracking_details->received_by = $user->id;
                $tracking_details->save();

                $tracking_release1 = Tracking_Releasev2::where('route_no', $route_no)
                	->orderBy('id','desc')
                	->first();

                if($tracking_release1 != null){
  					$tracking_release1->status ="accept";
  	               	$tracking_release1->save();
                }
            }
            $doc_id = Tracking_Details::where('route_no', $route_no)
                ->orderBy('id', 'desc')
                ->first()
                ->id;

            $release_to_datein = date('Y-m-d H:i:s');

            if ($doc_id != 0) {
                $table = Tracking_Details::where('id', $doc_id)->orderBy('id', 'DESC');
                $code = isset($table->first()->code) ? $table->first()->code : null;

                $tracking_release = new Tracking_Releasev2();
                $tracking_release->released_by = $user->id;
                $tracking_release->released_section_to = 5;
                $tracking_release->released_date = $release_to_datein;
                $tracking_release->remarks = $ors_no;
                $tracking_release->document_id = $table->first()->id;
                $tracking_release->route_no = $route_no;
                $tracking_release->status = "waiting";
                $tracking_release->save();

                $update = array(
                    'code' => null
                );

                $table->update($update);
                $tmp = explode(';', $code);
                $code = $tmp[0];
                if ($code == 'return') {
                    $table->delete();
                }
            } else {
                $tracking_details_info = Tracking_Details::where('route_no', $route_no)
                    ->orderBy('id', 'desc')
                    ->first();
                $tracking_details_id = $tracking_details_info->id;
                $update = array(
                    'code' => null
                );
                $table = Tracking_Details::where('id', $tracking_details_id);
                $table->update($update);
            }

            $q = new Tracking_Details();
            $q->route_no = $route_no;
            $q->date_in = $release_to_datein;
            $q->action = $ors_no;
            $q->delivered_by = $user->id;
            $q->code = 'temp;' . 5;
            $q->save();

            Session::put("releaseAdded", [
                "route_no" => $route_no,
                "section_released_to_id" => 5,
                "user_released_name" => $user->fname . ' ' . $user->lname,
                "section_released_by_id" => $user->section,
                "section_released_by_name" => Section::find(5)->description,
                "remarks" => $ors_no,
                "status" => "released"
            ]);

            return 0;
        }
    }

    static function paid($route_no, $user)
    {
        $user = Users::where('username', $user)->first();
        if (isset($route_no)) {
            $tracking_details = Tracking_Details::where('route_no', $route_no)
                ->orderBy('date_in', 'desc')
            	->first();

            if ($tracking_details->code == "temp;7") {
                $tracking_details->code = "accept;7";
                $tracking_details->received_by = $user->id;
                $tracking_details->save();

                $tracking_release1 = Tracking_Releasev2::where('route_no', $route_no)
                	->orderBy('id','desc')
                	->first();

                if($tracking_release1 != null){
  					$tracking_release1->status ="accept";
  	               	$tracking_release1->save();
                }
            }

            return 0;
        }
    }
}
