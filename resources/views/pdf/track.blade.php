<?php
use App\Tracking;
use App\Tracking_Details;
use App\User as User;
use App\Http\Controllers\DocumentController as Doc;
use App\Section;
$route_no = Session::get('route_no');
$document = Tracking::where('route_no',$route_no)->first();
$tracking = Tracking_Details::where('route_no',$route_no)
    ->orderBy('id','asc')
    ->get();
?>
<html>
<title>Track Details</title>
<style>
    .upper, .info, .table {
        width: 100%;
    }
    .upper td, .info td, .table td {
        border:1px solid #000;
    }
    .upper td {
        padding:10px;
    }
    .info {
        margin-top: 90px;
    }
    .info td {
        padding: 5px;
        vertical-align: top;
    }
    .table th {
        border:1px solid #000;
    }
    .table td {
        padding: 2px;
        vertical-align: top;
    }

    .route_no {
        font-size:1.2em;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
    }

    .barcode-container {
        display: table;
        margin: 0 auto;
        position: absolute;
        top: 130px;
    }
</style>
<body>
<div class="container">
    <div class="barcode-container">
        <?php echo DNS1D::getBarcodeHTML(Session::get('route_no'),"C39E",1,25) ?>
        <span class="route_no">{{ $route_no }}</span>
    </div>
</div>
<table class="upper" cellpadding="0" cellspacing="0">
    <tr>
        <?php $image_path = '/img/doh.png'; ?>
        <td width="20%"><center><img src="{{ public_path() . $image_path }}" width="100"></center></td>
        <td width="60%" style="font-size: 11pt;">
            <center>
                <span style="font-size: 12pt; font-family: Helvetica Neue">Republic of the Philippines</span><br>
                <span style="font-weight: bold; font-family: Helvetica Neue; font-size: 12pt">DEPARTMENT OF HEALTH</span><br>
                <span style="font-size: 12pt; font-family: Helvetica Neue; font-style: italic;">Central Visayas Center for Health Development</span><br><br>
                <span style="margin-top: 100px;">DOCUMENT TRACKING SYSTEM (DTS)</span>
            </center></td>
    <!--
                    {{--<td width="20%"><?php echo DNS2D::getBarcodeHTML(Session::get('route_no'), "QRCODE",5,5); ?></td>--}}
            -->
        <?php $image_path = '/img/bagong_pilipinas2.png'; ?>
        <td width="20%"><center><img src="{{ public_path() . $image_path }}" width="100"></center></td>
    </tr>
</table>
<table class="info" width="100%" cellspacing="0" style="margin-top: 60px;">
    <tr>
        <td width="30%">
            <strong>PREPARED BY:</strong><br>
            <?php $user = User::find($document->prepared_by); ?>
            {{ $user->fname.' '.$user->lname }}
        </td>
        <td>
            <strong>SECTION:</strong><br>
            {{ Section::find($user->section)->description }}
        </td>
        <td width="30%">
            <strong>PREPARED DATE:</strong><br>
            {{ date('F d, Y',strtotime($document->prepared_date)) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>DOCUMENT TYPE:</strong>
            {{ Doc::getDocType($route_no) }}
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <strong>REMARKS / SUBJECT:</strong>
            {!! nl2br($document->description) !!}
            <br>
            <br>
        </td>
    </tr>
</table>
<table cellspacing="0" class="table">
    <tr>
        <th width="15%">DATE</th>
        <th width="25%">RECEIVED BY</th>
        <th width="45%">ACTION / REMARKS</th>
        <th width="15%">SIGNATURE</th>
    </tr>
    @foreach($tracking as $doc)
        <?php
        $received_by = '';
        $section = '';
        if($doc->received_by==0){
            $string = $doc->code;
            $temp   = explode(';',$string);
            $section_id = isset($temp[1]) ? $temp[1] : 0;
            $action = isset($temp[0]) ? $temp[0]: 0;

            if($received_temp = Section::find($section_id)){
                $received_by = $received_temp->description;
            } else {
                $received_by = 0;
            }

            $user = User::find($doc->delivered_by);
            $tmp = $user->fname.' '.$user->lname;


            if($action=='temp')
            {
                $section = 'Unconfirmed';
            }else if($action==='return'){
                $section = 'Returned';
            }
        }else{
            if($user = User::find($doc->received_by)){
                $received_by = $user->fname.' '.$user->lname;
                if($section = Section::find($user->section)){
                    $sectionName = $section->description;
                } else {
                    $sectionName = "No Section";
                }
            } else {
                $received_by = "No Name";
            }


        }
        ?>
        @if(($doc->received_by != $doc->delivered_by))
            <tr>
                <td>{{ date('M d, Y', strtotime($doc->date_in)) }}<br>{{ date('h:i A', strtotime($doc->date_in)) }}</td>
                <td>
                    {{ $received_by }}
                    <br>
                    <em>({{ $sectionName }})</em>
                </td>
                <td>{{ $doc->action}}</td>
                <td></td>
            </tr>
        @endif
    @endforeach
    <?php $i = count($tracking); ?>
    @for($i; $i < 16; $i++)
        <tr>
            <td>&nbsp;<br><br></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    @endfor
</table>
</body>
</html>