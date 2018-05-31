@extends('layouts.app')

@section('content')

<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> My Appointments</h1>
<!-- END PAGE TITLE-->    

@include('notification')

<div class="tabbable-custom nav-justified">
    <ul class="nav nav-tabs nav-justified hidden-xs">
        <li class="{{ actived($status, 'pending') }}">
            <a href="{{ URL::route('owner.appointments.index', ['status' => 'pending']) }}"> Pending<br> Appointments 
            @if($pending)
            <b class="label label-sm label-danger pull-right">{{ number_format($pending) }}</b>
            @endif
            </a>
        </li>
        <li class="{{ actived($status, 'confirmed') }}">
            <a href="{{ URL::route('owner.appointments.index', ['status' => 'confirmed']) }}"> Confirmed<br> Appointments 
            @if($confirmed)
            <b class="label label-sm label-danger pull-right">{{ number_format($confirmed) }}</b>
            @endif
            </a>
        </li>
        <li class="{{ actived($status, 'for_approval') }}">
            <a href="{{ URL::route('owner.appointments.index', ['status' => 'for_approval']) }}"> Completed<br> (For Approval) 
            @if($for_approval)
            <b class="label label-sm label-danger pull-right">{{ number_format($for_approval) }}</b>
            @endif
            </a>
        </li>
        <li class="{{ actived($status, 'completed') }}">
            <a href="{{ URL::route('owner.appointments.index', ['status' => 'completed']) }}"> Completed<br> Appointments
            @if($completed)
            <b class="label label-sm label-danger pull-right">{{ number_format($completed) }}</b>
            @endif
            </a>
        </li>
        <li class="{{ actived($status, 'cancelled') }}">
            <a href="{{ URL::route('owner.appointments.index', ['status' => 'cancelled']) }}"> Cancelled<br> Appointments
            @if($cancelled)
            <b class="label label-sm label-danger pull-right">{{ number_format($cancelled) }}</b>
            @endif
            </a>
        </li>
    </ul>



    <form method="get" class="visible-xs">
    <div class="input-group">
        {{ Form::select('status', appointment_status(), Input::get('status'), ['class' => 'form-control'] ) }}
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </span>
    </div>
    </form>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1_1_1">

            <h3>{{ ucwords($status) }} Appointments</h3>

             <table class="table table-striped table-bordered table-hover datatable">
                <thead>
                    <tr>
                        <th>ID#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Start / End</th>
                        <th>Breaktime</th>
                        <th>Hours</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($rows as $row)
                    <?php $postmeta = get_meta($row->postMetas()->get()); ?>
                    <tr class="{{ ($row->id == Input::get('id')) ? 'actived' : '' }}">
                        <td>{{ $row->id }}</td>
                        <td>
                        <a href="{{ URL::route('owner.dentalpro.profile', $postmeta->user_id) }}"  class="uppercase">
                        <?php $user = App\User::find($postmeta->user_id); ?>
                        {!! name_formatted(@$user->id) !!}
                        </a>
                        </td>
                        <td>
                        
                        @if(@$postmeta->user_id)
                        {{ provider_type($user->get_meta($postmeta->user_id, 'provider_type')) }}
                        @endif

                        </td>
                        <td>

                        @if(@$postmeta->start_time)
                        {{ get_times($postmeta->start_time) }} - {{ get_times($postmeta->end_time) }}               
                        @endif

                        </td>
                        <td>

                        @if(@$postmeta->breaktime)
                        {{ breaktime($postmeta->breaktime) }}
                        @endif

                        </td>
                        <td>
                        {{ $postmeta->total_time }}             
                        </td>
                        <td> {{ amount_formatted($postmeta->total_amount) }}   </td>
                        <td>{{ date_formatted($postmeta->date) }}
                        </td>
                        <td>
                            <a data-href="{{ URL::route('owner.appointments.view', $row->id) }}" 
                            class="btn btn-xs btn-success uppercase btn-view" 
                            data-toggle="modal" 
                            href="#view">View</a>
                            
                            @if(in_array($row->post_status, ['pending', 'confirmed']))
                            <a href="#" class="delete btn btn-xs btn-danger uppercase"
                                data-href="{{ URL::route('owner.appointments.status', ['cancelled', $row->id]) }}" 
                                data-toggle="modal"
                                data-target=".delete-modal" 
                                data-title="Cancel Appointment"
                                data-body="Are you sure you want to cancel appointment with <b>ID# {{ $row->id }}</b>?">Cancel</a>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</div>




<div class="modal fade in" id="view" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

            </div>

            <div class="modal-body"></div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style>
li.active a {
    font-weight: bold!important;
}
table.dataTable.no-footer {
    border-bottom-color: #e7ecf1;	
}	
tr.actived, table.dataTable tr.actived td.sorting_1 { background: #fff7ad!important; }
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>
$(document).on('click', '.btn-view', function() {
    var url = $(this).data('href');
    $.get(url, function(res) {
        $('#view .modal-body').html(res);
    })
});

$('.datatable').dataTable({
    "order": [[ 0, "desc" ]]
});

$('input[type="search"]').val('{{ Input::get('id') }}').trigger($.Event("keyup", { keyCode: 13 }));
</script>


@include('partials.delete-modal')

@stop
