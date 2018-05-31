@extends('layouts.app')

@section('content')

<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> My Job Postings - <small>{{ number_format(count($rows)) }} Job{{ is_plural(count($rows)) }}</small>

@if( App\User::get_meta($user_id, 'package_amountx') )
<a href="#owner-trial-period" data-toggle="modal" class="btn btn-primary btn-circle uppercase pull-right"><i class="fa fa-plus"></i> Post A New Job</a>
@endif

@if( $info->lat && $info->lng )
<a href="{{ URL::route('owner.job-postings.add') }}" class="btn btn-primary btn-circle uppercase pull-right"><i class="fa fa-plus"></i> Post A New Job</a>
@endif

</h1>
<!-- END PAGE TITLE-->    
@if( $info->lat && $info->lng )
@else
    <div class="alert alert-danger"><h4>Your location could not be determined!</h4> Please make sure you give us your correct address.
    <a href="{{ URL::route('owner.accounts.settings', ['tab' => 5]) }}" class="sbold">Edit My Address</a></div> 
@endif

<form method="get" class="form-horizontal">
<div class="form-body">
    <div class="form-group">
        <div class="col-md-3">
            <label class="uppercase small text-muted">Job Status</label>
            {!! Form::select('job_status', ['' => 'All'] + job_status(), Input::get('job_status'), ["class" => "form-control"]) !!}

        </div>      
        <div class="col-md-3">
            <label class="uppercase small text-muted">Post Status</label>
            {!! Form::select('status', ['' => 'All'] + job_post_status(), Input::get('status'), ["class" => "form-control"]) !!}
        </div>
        
    </div>



    <div class="form-group">
        <div class="col-md-12">
        <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-search"></i> Search</button>
        <a href="{{ URL::route('owner.job-postings.index') }}" class="btn">Clear Search</a>
        </div>      
    </div>

</div>
</form>

<div class="table-responsive">
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Job Title</th>
            <th>Details</th>
            <th>Applicants</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
         <?php $postmeta = get_meta($row->postMetas()->get()); ?>

        <tr>
            <td>{{ $row->post_title }}</td>
            <td>
                <small class="uppercase">
                <i class="fa fa-clock-o text-muted small"></i> 
                {{ @job_type($row->get_meta($row->id, 'job_type')) }}<br>
                <i class="fa fa-money text-muted small"></i> 
                {{ salary_prefix_formatted($row->get_meta($row->id, 'salary_type'), $row->get_meta($row->id, 'salary_rate')) }}<br>
                <i class="fa fa-calendar text-muted small"></i> 
                {{ @years_of_experience($row->get_meta($row->id, 'years_of_experience')) }}<br>
                </small>
            </td>
            <td>
            <table width="100%">
                <tr>
                    <td width="55">Applied</td>
                    <td> : {{ number_format($row->where('parent', $row->id)->count()) }}</td>
                </tr>
                <tr>
                    <td>Slot</td>
                    <td> : {{ @$postmeta->number_of_position }} / {{ number_format($row->where(['post_type' => 'application', 'post_status' => 'hired', 'parent' => $row->id])->count()) }}</td>
                </tr>
            </table>
            </td>
            <td>    
                {{ date_formatted($row->created_at) }}<br>
                <small class="text-muted">{{ time_ago($row->created_at) }}</small>
            </td>
            <td>
                {{ status_ico($row->get_meta($row->id, 'job_status')) }}
                {{ status_ico($row->post_status) }}

                @if(@$postmeta->hiring_status)
                    <br><label class="badge badge-danger uppercase sbold margin-top-10">Urgent Hiring <i class="fa fa-exclamation"></i></label>  
                @endif

            </td>
            <td>
                <a href="{{ URL::route('owner.job-postings.view', $row->id) }}" class="btn btn-success btn-xs uppercase">View</a>
                <a href="{{ URL::route('owner.job-postings.edit', $row->id) }}" class="btn btn-primary btn-xs uppercase">Edit</a>

                @if($postmeta->job_status == 'close')
                <a href="#" class="delete btn btn-xs btn-default uppercase"
                    data-href="{{ URL::route('owner.job-postings.delete', $row->id) }}" 
                    data-toggle="modal"
                    data-target=".delete-modal" 
                    data-title="Confirm Delete"
                    data-body="Are you sure you want to <b>delete post</b> with <b>ID# {{ $row->id }}</b>?">Delete</a>
                @else
                <button class="btn btn-xs btn-default uppercase" disabled>Delete</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<?php parse_str(query_vars(), $appends); ?>
{{ $rows->appends($appends)->links() }}

@if(count($rows) == 0)
<h3 class="text-center">No job posting found!</h3>
@endif

@endsection



@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
@include('partials.delete-modal')
@stop
