@extends('layouts.app')

@section('content')


<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> My Jobs - <small>{{ number_format(count($rows)) }} Job{{ is_plural(count($rows)) }}</small></h1>
<!-- END PAGE TITLE-->    


<div class="table-responsive">
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>JOB ID</th>
            <th>Job Title</th>
            <th>Date Applied</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($rows as $row): ?>
    <tr>
        <td><span class="text-muted small">{{ $row->id }}</span></td>
        <td>
            <a href="{{ URL::route('provider.job-postings.view', $row->parent) }}">{{ $row->find($row->parent)->post_title }}</a><br>  
            <small class="text-muted">Posted by : {{ ucwords($user->find($row->post_title)->fullname) }}</small> 
        </td>
        <td>
            {{ date_formatted($row->created_at) }}<br>
            <small class="text-muted">{{ time_ago($row->created_at) }}</small>
        </td>
        <td>
        {{ status_ico($row->post_status) }}
        
        @if($row->post_status == 'hired')<br>
        {{ date_formatted($row->get_meta($row->id, 'date_hired'))  }}<br>
        <small class="text-muted">{{ time_ago($row->get_meta($row->id, 'date_hired'))  }}</small>
        @endif

        </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
</table>
</div>

@if(count($rows) == 0)
<h3 class="text-center">No job application at this moment, <a href="{{ URL::route('provider.job-postings.index') }}">find your job now!</a></h3> 
@endif

{{ $rows->links() }}

@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.select2-selection {
  min-height: 34px;
  padding: 3px;
  border: 1px solid #c2cad8!important;
}
.select2-selection__arrow {
  padding: 15px;
}
.sched-box {
    border: 1px solid #d6d4d5;
}
.available {
    background: #ffefae;                
}
.result {
    border: 1px solid #d6d4d5;
    margin-bottom: 25px;
    padding: 20px;
}
.result:hover {
    background: #fbfbfb;    
}
</style>


@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>    

@stop

@section('bottom_script')
<script>
    $(document).on('click', '.btn-filter', function(e) {
    e.preventDefault();
    var form = $('.form-filter');
    if( form.hasClass('show') ) {
        $('.form-filter').hide();                
        form.removeClass('show');
        $('.btn-filter span').text('Show');
        $('[name=filter]').val('hide');
    } else {
        $('.form-filter').show();        
        form.addClass('show');
        $('.btn-filter span').text('Hide');
        $('[name=filter]').val('show');
    }
});

$(".datepicker").datepicker();

$(".select2").select2({width: '100%'});
</script>
@include('partials.delete-modal')
@stop
