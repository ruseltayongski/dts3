<?php
    use App\Tracking_Details;
?>
@extends('layouts.app')

@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="alert alert-jim" id="inputText">
    <style>
        .action .btn{
            width:100%;
            margin-bottom: 5px;
        }
    </style>
    <h2 class="page-header">Cycle End from Record</h2>
    <form class="form-inline" method="POST" action="{{ asset('cycle/end') }}" onsubmit="return searchDocument();" id="searchForm">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Quick Search" name="keyword" value="{{ Session::get('keyword') }}" autofocus>
            {{--<div class="input-group">--}}
                {{--<div class="input-group-addon">--}}
                    {{--<i class="fa fa-calendar"></i>--}}
                {{--</div>--}}
                {{--<input type="text" class="form-control" id="reservation" name="daterange" value="{{ isset($daterange) ? $daterange: null }}" placeholder="Input date range here..." required>--}}
            {{--</div>--}}
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
        </div>
    </form>
    <div class="clearfix"></div>
    <div class="page-divider"></div>
    @if(count($documents))
    <div class="table-responsive">
        <table class="table table-list table-hover table-striped">
            <thead>
                <tr>
                    <th width="8%"></th>
                    <th width="20%">Route #</th>
                    <th width="15%">Prepared Date</th>
                    <th width="20%">Document Type</th>
                    <th>Remarks / Additional Information</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td class="action">
                        <a href="#track" data-route="{{ $doc->route_no }}" data-link="{{ asset('document/track/'.$doc->route_no) }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a>
                    </td>
                    <td>{{ $doc->route_no }}
                    </td>
                    <td>{{ date('M d, Y',strtotime($doc->prepared_date)) }}<br>{{ date('h:i:s A',strtotime($doc->prepared_date)) }}</td>
                    <td>{{ \App\Http\Controllers\DocumentController::docTypeName($doc->doc_type) }}</td>
                    <td>
                        @if($doc->doc_type == 'PRR_S')
                            {!! nl2br($doc->purpose) !!}
                        @else
                            {!! nl2br($doc->description) !!}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $documents->links() }}
    @else
        <div class="alert alert-danger">
            <strong><i class="fa fa-times fa-lg"></i> No documents found! </strong>
        </div>
    @endif
</div>
@endsection
@section('plugin_old')
<script>
    function searchDocument(){
        $('.loading').show();
        setTimeout(function(){
            return true;
        },2000);
    }
    $("a[href='#track']").on('click',function(){
        var route_no = $(this).data('route');
        $('.modal-title').html(route_no);
    });

</script>
@endsection



