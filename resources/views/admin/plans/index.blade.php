@extends('layouts.app')

@section('content')


<div class="row margin-top-20">
        <div class="col-md-12">

            @include('notification')


            @if(Input::get('type'))
            <a href="{{ URL::route('admin.plans.index', query_vars('type=0&s=0')) }}">All ({{ $all }})</a> | 
            <b>Trashed ({{ $trashed }})</b>
            @else
            <b>All ({{ $all }})</b> | 
            <a href="{{ URL::route('admin.plans.index', query_vars('type=trash&s=0')) }}">Trashed ({{ $trashed }})</a>
            @endif


        <a href="{{ URL::route('admin.plans.add') }}" class="btn btn-primary btn-md btn-circle uppercase pull-right"><i class="fa fa-plus"></i> Add Plan</a>

        <h1 class="page-title"> Plans</h1>
    </div>
</div>

<div class="text-muted h5"><strong>{{ $count }}</strong> Result{{ is_plural($count) }}</div>

<div class="table-responsive">
<table class="table table-sriped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Date Modified</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
            <td>
                <h4 class="no-margin"><a href="{{ URL::route('admin.plans.edit', $row->id) }}">{{ $row->post_title }}</a></h4>

                <div class="margin-top-5">
                    @if( Input::get('type') == 'trash')
                        <a href="#" class="delete btn btn-xs btn-primary uppercase margin-top-10"
                            data-href="{{ URL::route('admin.plans.restore', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Restore"
                            data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> 

                        <a href="#" class="delete btn btn-xs btn-default uppercase margin-top-10"
                            data-href="{{ URL::route('admin.plans.destroy', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Delete Permanently"
                            data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                    @else

                        <a href="{{ URL::route('admin.plans.edit', $row->id) }}" class="btn green btn-xs uppercase margin-top-10">Edit</a>

                        @if($row->status != 1)
                        <a href="#" class="delete btn btn-xs btn-default uppercase margin-top-10"
                            data-href="{{ URL::route('admin.plans.delete', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Delete"
                            data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a> 
                        @else
                        <button class="btn btn-xs btn-default uppercase margin-top-10" disabled>Move to trash</button>
                        @endif    
                    @endif                     
                </div>

            </td>
            <td>{{ status_ico($row->post_status) }}</td>
            <td>
                {{ date_formatted($row->updated_at) }}<br>
                <small>{{ time_ago($row->updated_at) }}</small>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@if( count($rows) == 0)
<h3 class="text-center">No plans created at this moment would you like to <a href="{{ URL::route('admin.plans.add') }}">create one?</a></h3>
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