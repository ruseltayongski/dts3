@if(count($document))
    <style>
        .trackFontSize{
            font-size: 8pt;
        }
    </style>
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th width="5%"></th>
            <th width="15%">Route No</th>
            <th width="10%">Doc Type</th>
            <th width="15%">Prepared Date</th>
            <th width="20%">Prepared By</th>
            <th width="35%">Description</th>
        </tr>
        </thead>
        <tbody>
            @foreach($document as $row)
                <tr>
                    <td class="text-bold">
                        <a href="javascript:void(0);" data-route="{{ $row->route_no }}"
                           data-link="{{ asset('document/track/'.$row->route_no) }}"
                           class="btn btn-sm btn-success col-sm-12 open-track-doc">
                            <i class="fa fa-line-chart"></i> Track
                        </a>

                        {{--<a href="#track" data-link="{{ asset('document/track/'.$row->route_no) }}" data-toggle="modal" class="btn btn-sm btn-success col-sm-12"><i class="fa fa-line-chart"></i> Track</a>--}}
                    </td>
                    <td class="text-bold">{!! $row->highlighted_route_no !!}</td>
                    <td class="trackFontSize">{{ $row->doc_type }}</td>
                    <td class="trackFontSize">
                        {{ date('F j, Y', strtotime($row->prepared_date)) }}
                        <br>
                        {{ date('h:i a', strtotime($row->prepared_date)) }}
                    </td>
                    <td class="trackFontSize">
                        {{ $row->user_prepared ? $row->user_prepared->fname .' '. $row->user_prepared->lname : '' }}
                    </td>
                    <td class="trackFontSize">{!! nl2br($row->highlighted_description) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right">
        {{ $document->appends(['keyword' => $keyword])->links() }}
    </div>
@else
    <div class="alert alert-danger">
        <i class="fa fa-times"></i> No document found!
    </div>
@endif
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        $('.track_search_history').html(loadingState);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('.track_search_history').html(data);
            },
            error: function() {
                $('.track_search_history').html('<div class="alert alert-danger"><i class="fa fa-times"></i> Error loading page.</div>');
            }
        });
    });

    $(document).on('click', '.open-track-doc', function(e) {
        e.preventDefault();
        var url = $(this).data('link');
        var route_no = $(this).data('route');

        $('#trackDoc').modal({
            backdrop: false,
            keyboard: false,
            show: true
        });
        $('#trackDoc .track_history').html(loadingState);
        $.ajax({
            url: url,
            success: function(data) {
                $('#trackDoc .track_history').html(data);
                $('#track_route_no2').val(route_no).attr('readonly', true);

            }
        });
        $('body').addClass('modal-open');
    });
</script>