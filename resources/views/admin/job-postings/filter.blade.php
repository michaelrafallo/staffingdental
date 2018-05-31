@include('notification')


@if(Input::get('type'))
<a href="{{ URL::route('admin.job-postings.index', query_vars('type=0&s=0')) }}">All ({{ $all }})</a> | 
<b>Trashed ({{ $trashed }})</b>
@else
<b>All ({{ $all }})</b> | 
<a href="{{ URL::route('admin.job-postings.index', query_vars('type=trash&s=0')) }}">Trashed ({{ $trashed }})</a>
@endif

<form action="" method="GET" class="form-horizontal">

<div class="portlet-body form">

    <div class="form-body form-filter {{ (Input::get('filter') == 'show') ? 'show' : 'display-nonex' }}">

    <div class="row">

        <div class="form-group">

            <div class="col-md-3">
            <label class="control-label">Search Job</label>
            <input type="text" class="form-control" name="s" value="{{ Input::get('s') }}" placeholder="Search Job ...">
            </div>

                <div class="col-md-2">
                <label class="control-label">Job Type</label>
                    {!! Form::select('job_type', ['' => 'All Job Type'] + job_type(), Input::get('job_type'), ["class" => "select2 form-control"]) !!}
                </div>

                <div class="col-md-2">
                    <label class="control-label">Job Status</label>
                    {!! Form::select('job_status', ['' => 'All Status'] + job_status(), Input::get('job_status'), ["class" => "select2 form-control"]) !!}
                </div>

                <div class="col-md-2">
                    <label class="control-label">Post Status</label>
                    {!! Form::select('status', ['' => 'All Status'] + job_post_status(), Input::get('status'), ["class" => "select2 form-control"]) !!}
                </div>

                <div class="col-md-2">
                    <label class="control-label">Per Page</label>
                    {!! Form::select('rows', [15 => 15, 25 => 25, 50 => 50, 100 => 100], Input::get('rows'), ["class" => "select2 form-control"]) !!}
                </div>

                <div class="col-md-2">
                    <label class="control-label">Sort</label>
                    {!! Form::select('sort', ['DESC' => 'Newest', 'ASC' => 'Oldest'], Input::get('sort'), ["class" => "select2 form-control"]) !!}
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn blue margin-top-10" type="button"><i class="fa fa-search"></i> Search</button>
                    <a href="{{ URL::route('admin.job-postings.index') }}" class="btn btn-default margin-top-10"><i class="fa fa-times"></i> Clear Search</a>
                    
                </div>
  
        </div>


        </div>

    </div>
</div>

</form>

<div class="text-muted pull-right h5"><strong>{{ $count }}</strong> Result{{ is_plural($count) }}</div>

<form method="post" action="{{ URL::route('admin.job-postings.index', query_vars()) }}">
    {!! csrf_field() !!}

<div class="row">
    <div class="col-md-3">

    <div class="input-group">
        {!! Form::select('action', ['' => '-- Select Action --'] + form_actions(), Input::old('group'), ["class" => "form-control"]) !!}    
        <span class="input-group-btn">
            <button type="submit" class="btn btn-success" type="button">Go</button>
        </span>
    </div>

    </div>    
</div>


