<?php
use App\Http\Controllers\SectionController as Sec;
use App\Http\Controllers\AdminController as Admin;
?>
@extends('layouts.app')

@section('content')

    <div class="alert alert-jim" id="inputText">
        <h2 class="page-header">Released Documents - 2023</h2>

        <!-- Nav tabs -->
        <?php
        $monthArray = ['01' => 'January', '02' => "February",'03' => "March",'04' => "April",'05' => "May",'06' => "June",'07' => "July",'08' => "August",'09' => "September",'10' => "October",'11' => "November",'12' => "December"];
        $thHeadColor = ['text-success','text-info','text-warning','text-danger'];
        ?>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="January" role="tabpanel" aria-labelledby="home-tab">
                {{--<div class="clearfix"></div>
                <div class="page-divider"></div>--}}
                <?php $count = 0; ?>
                    @if(isset($thHeadColor[$count]))
                        <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                            <thead>
                            <tr>
                                <th colspan="2" class="bg-{{ explode('-',$thHeadColor[$count])[1] }} text-bold {{ $thHeadColor[$count] }} text-uppercase" style="padding: 15px 10px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> Released Documents from ICTU</td>
                                </tr>
                                @foreach($monthArray as $key => $month)
                                <tr>
                                    <?php
                                        $count = \App\Tracking_Releasev2::where("released_by","=",985452)
                                        ->whereMonth('released_date',"=",$key)
                                        ->whereYear('released_date',"=", 2023)
                                        ->count();
                                        ?>
                                    <td class="col-sm-6">{{$month}}</td>
                                    <td class="col-sm-6">{{$count}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
            </div>

            <div class="tab-content">
                <div class="tab-pane fade active in" id="January" role="tabpanel" aria-labelledby="home-tab">
                    {{--<div class="clearfix"></div>
                    <div class="page-divider"></div>--}}
                    <?php $count = 0; ?>
                    @if(isset($thHeadColor[$count]))
                        <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                            <thead>
                            <tr>
                                <th colspan="2" class="bg-{{ explode('-',$thHeadColor[$count])[1] }} text-bold {{ $thHeadColor[$count] }} text-uppercase" style="padding: 15px 10px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> Released Documents from RD's office - Accepted </td>
                            </tr>
                            @foreach($monthArray as $key => $month)
                                <tr>
                                    <?php
                                    $count = \App\Tracking_Releasev2::where("released_by","=",986000)
                                        ->where('status',"=", "accept")
                                        ->whereMonth('released_date',"=",$key)
                                        ->whereYear('released_date',"=", 2023)
                                        ->count();
                                    ?>
                                    <td class="col-sm-6">{{$month}}</td>
                                    <td class="col-sm-6">{{$count}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade active in" id="January" role="tabpanel" aria-labelledby="home-tab">
                        {{--<div class="clearfix"></div>
                        <div class="page-divider"></div>--}}
                        <?php $count = 0; ?>
                        @if(isset($thHeadColor[$count]))
                            <table class="table table-striped table-hover" style="border: 1px solid #d6e9c6">
                                <thead>
                                <tr>
                                    <th colspan="2" class="bg-{{ explode('-',$thHeadColor[$count])[1] }} text-bold {{ $thHeadColor[$count] }} text-uppercase" style="padding: 15px 10px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td> Released Documents from RD's office - Not accepted </td>
                                </tr>
                                @foreach($monthArray as $key => $month)
                                    <tr>
                                        <?php
                                        $count = \App\Tracking_Releasev2::where("released_by","=",986000)
                                            ->whereMonth('released_date',"=",$key)
                                            ->whereYear('released_date',"=", 2023)
                                            ->where('status','=','waiting')
//                                            ->where(function ($query) {
//                                                $query->where('status','=','waiting')
//                                                    ->orWhere('status','=','report');

                                            ->count();
                                        ?>
                                        <td class="col-sm-6">{{$month}}</td>
                                        <td class="col-sm-6">{{$count}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
        </div>

    </div>


@endsection
@section('plugin')

@endsection

@section('css')

@endsection

