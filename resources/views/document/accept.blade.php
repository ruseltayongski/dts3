@extends('layouts.app')
@section('content')
<div class="col-md-12 wrapper">
    <div class="alert alert-jim">
        @if (session('status'))
            <?php
                $status = session('status');
            ?>
            @if(isset($status['success']))
                <div class="alert alert-success">
                    <ul>
                        @foreach ($status['success'] as $success)
                            <li>{!! $success !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(isset($status['errors']))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($status['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
        <h2 class="page-header">Accept Documents</h2>
        <form class="form-accept form-submit" id="accept_form" method="post">
            {{ csrf_field() }}

            {{--<div class="form-inline form-group">--}}
                {{--<input type="text" name="route_no" class="form-control route_no" disabled placeholder="Enter route #" autofocus>--}}
                {{--<input type="text" name="remarks" class="form-control remarks" disabled placeholder="Enter remarks">--}}
            {{--</div>--}}

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Accepted By</th>
                        <th>Date In</th>
                        <th>Route No / Barcode</th>
                        <th>Remarks</th>
                        @if(Auth::user()->section == 36)
                        <th>Click if office order is approve</th>
                        @endif
                        {{--@if(Auth::user()->section == 5)--}}
                        {{--<th>DV No.</th>--}}
                        {{--@endif--}}
                    </tr>
                </thead>
                <tbody>
                @for($i=0;$i<10;$i++)
                    <tr>
                        <td>
                            {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                        </td>
                        <td>
                            {{ date('M d, Y h:i:s A') }}
                        </td>
                        <td>
                            <input type="text" name="route_no[]" class="form-control route_no" disabled placeholder="Enter route #">
                        </td>
                        <td>
                            <textarea class="form-control remarks" name="remarks[]" disabled></textarea>
                        </td>
                        @if(Auth::user()->section == 36)
                        <td>
                            <a href="#{{ $i.'collapseSono' }}" type="button" class="click_me" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
                                <small>ADD SO#</small>
                            </a>
                            <div class="collapse" id="{{ $i.'collapseSono' }}">
                                <input type="hidden" id="{{ 'input'.$i.'collapseSono' }}" class="form-control" name="so_no[]" placeholder="Enter SO#" required>
                            </div>
                        </td>
                        @endif
                        {{--@if(Auth::user()->section == 5)--}}
                            {{--<td>--}}
                                {{--<a href="#{{ $i.'collapseDvno' }}" type="button" class="click_me" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">--}}
                                    {{--<small>ADD DV#</small>--}}
                                {{--</a>--}}
                                {{--<div class="collapse" id="{{ $i.'collapseDvno' }}">--}}
                                    {{--<input type="hidden" id="{{ 'input'.$i.'collapseDvno' }}" class="form-control" name="dv_no[]" placeholder="Enter DV#" required>--}}
                                {{--</div>--}}
                            {{--</td>--}}
                        {{--@endif--}}
                    </tr>
                @endfor
                <tr>
                    <td colspan="4" class="text-right">
                        <button type="submit" class="btn btn-success btn-lg btn-accept btn-submit"><i class="fa fa-plus"></i> Accept Document</button>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="clearfix"></div><br>
            <div class="alert alert-danger error-accept hide">Please input route number!</div>
        </form>
        <hr />
        <div class="accepted-list">

        </div>
    </div>
</div>

@endsection

@section('js')
    <script>
        @if(Auth::check() && Auth::user()->section == 36)
            $(".click_me").each(function(index){
                var href = $(this).attr('href');
                $("a[href='"+href+"']").on("click",function(){
                    console.log("input"+$(this).attr('href').split("#")[1]);
                    if( $($(this).attr('href')).is(":hidden") ){
                        $("#input"+$(this).attr('href').split("#")[1]).attr('type', 'number');
                    }
                    else {
                        $("#input"+$(this).attr('href').split("#")[1]).attr('type', 'hidden');
                    }

                });
            });
        @endif

        <?php echo 'var url="'. asset('document/accept').'";'; ?>
        var route_nos = [];
//        $('.form-accept').on('submit',function(e){
//            $('.loading').show();
//            var remarks = $('.remarks').val();
//            var route_no = $('.route_no').val();
//            var content = '<div class="alert alert-info"><span class="pull-right"><a href="#" class="remove-accept" data-route="'+route_no+'"><i class="fa fa-times"></i></a></span><strong>ACCEPTED!</strong><br>Route Number: <strong>'+route_no+'</strong><br>Remarks: '+remarks+'</div>';
//            if(route_no){
//                for(var i=0; i<route_nos.length; i++){
//                    if(route_nos[i]==route_no){
//                        $('.error-accept').removeClass('hide').fadeIn(500).html('Route # \''+route_no+'\' is already accepted!');
//                        $('.loading').hide();
//                        return false;
//                    }
//                }
//                //post data to database
//                var data = [$('.route_no').val, $('.remarks').val];
//                var form = $('#accept_form');
//                $.ajax({
//                    url: url,
//                    type: 'POST',
//                    data: form.serialize(),
//                    success: function(data) {
//                        $('.loading').hide();
//                        var jim = jQuery.parseJSON(data);
//                        if(jim.message=='SUCCESS'){
//                            route_nos.push(route_no);
//                            $('.accepted-list').append(content);
//                            $('.route_no').val(null).focus();
//                            $('.remarks').val(null);
//                            $('.error-accept').addClass('hide').fadeOut(500);
//
//                            //if remove accept
//                            $('.remove-accept').on('click',function(){
//                                $('.loading').show();
//                                var tmp = $(this).data('route');
//                                $(this).parent().parent().fadeOut(500);
//                                for(var i=0; i<route_nos.length; i++){
//                                    if(route_nos[i]==tmp){
//                                        route_nos.splice(i,1);
//                                        $.ajax({
//                                            url: 'destroy/'+tmp,
//                                            type: 'GET',
//                                            success: function(data) {
//                                                $('.loading').hide();
//                                            }
//                                        });
//                                    }
//                                }
//                            });
//
//                        }else{
//                            $('.error-accept').removeClass('hide').fadeIn(500).html('Route # \''+route_no+'\' not found in the database!');
//                            return false;
//                        }
//
//                    },
//                    error: function () {
//                        console.log('error');
//                    }
//                });
//
//
//            }else{
//                $('.error-accept').removeClass('hide').fadeIn(500).html('Please input route number!');
//                $('.route_no').focus();
//                $('.loading').hide();
//            }
//
//            e.preventDefault();
//            return false;
//        });

        $(window).load(function(){
            $('.route_no').prop("disabled", false); // Element(s) are now enabled.
            $('.remarks').prop("disabled", false); // Element(s) are now enabled.
        });


        {{--@if(Auth::check() && Auth::user()->section == "5")--}}

            {{--var ajaxRequestMade = false;--}}
            {{--$(document).on('input',function(){--}}

                {{--if (!ajaxRequestMade) {--}}
                {{--// Retrieve the value of the input element by its class--}}
                {{--var value = $(".route_no").val();--}}

                {{--<?php echo 'var url ="'.asset('document/route_no').'";';?>--}}

                {{--// Adding a timeout of 500 milliseconds before making the AJAX request--}}
                {{--setTimeout(function() {--}}
                    {{--$.ajax({--}}
                        {{--url: url,--}}
                        {{--data: { route_no: value },--}}
                        {{--dataType: 'json',--}}
                        {{--timeout: 1000, // Timeout set to 5 seconds (you can adjust this value as needed)--}}
                        {{--success: function(data) {--}}
                            {{--console.log("Success:", data.section);--}}
                            {{--if(data.section == 105) {--}}
                                {{--ajaxRequestMade = true;--}}
                                {{--$(".click_me").each(function(index){--}}
                                    {{--var href = $(this).attr('href');--}}
                                    {{--$("a[href='"+href+"']").on("click",function(){--}}
                                        {{--console.log("input"+$(this).attr('href').split("#")[1]);--}}
                                        {{--if( $($(this).attr('href')).is(":hidden") ){--}}
                                            {{--$("#input"+$(this).attr('href').split("#")[1]).attr('type', 'number');--}}
                                        {{--}--}}
                                        {{--else {--}}
                                            {{--$("#input"+$(this).attr('href').split("#")[1]).attr('type', 'hidden');--}}
                                        {{--}--}}

                                    {{--});--}}
                                {{--});--}}
                            {{--}--}}
                        {{--},--}}
                        {{--error: function(xhr, status, error) {--}}
                            {{--console.error("Error:", error);--}}
                        {{--}--}}
                    {{--});--}}
                {{--}, 500); // Adjust the delay as needed (500 milliseconds in this case)--}}
            {{--}--}}
        {{--});--}}
        {{--@endif--}}


    </script>
@endsection