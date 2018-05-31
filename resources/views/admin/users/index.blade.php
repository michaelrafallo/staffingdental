@extends('layouts.app')

@section('content')
<h1 class="page-title"> Users</h1>


@include('admin.users.filter')

    <div class="text-muted pull-right h5"><strong>{{ $count }}</strong> Result{{ is_plural($count) }}</div>

    <form method="post" action="{{ URL::route('admin.users.index', query_vars()) }}">
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



<div class="table-responsive">
<table class="table table-stripe table-hover">
    <thead>
        <tr>
            <th width="1">
                <label class="mt-checkbox mt-checkbox-outline">
                <input id="check_all" type="checkbox" name="enable_upgrade" value="1">
                <span></span>
                </label>
            </th>
            <th width="1" colspan="2">Details</th>
            <th>Status</th>
            <th>Details</th>
            <th>Last Login</th>
            <th>Date Registered</th>
        </tr>
    </thead>
    <tbody>

        @foreach($rows as $row)
        <?php 
        $usermeta = get_meta($row->userMetas()->get()); 
        ?>
        <tr>
            <td>
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" name="ids[]" value="{{ $row->id }}">
                    <span></span>
                    </label>
            </td>        
            <td width="1">
                <img src="{{ has_photo(@$usermeta->profile_picture) }}" class="img-responsive img-thumb"> 
            </td>
            <td>
                <h4 class="no-margin"><a href="{{ URL::route('admin.users.edit', $row->id) }}">{{ ucwords($row->fullname) }}</a></h4>
                
                <small class="text-muted">ID: {{ $row->id }}</small>
                <small>{{ $row->email }}</small>

                <div class="margin-top-5">
                    @if( Input::get('type') == 'trash')
                        <a href="#" class="delete btn btn-xs btn-primary uppercase margin-top-10"
                            data-href="{{ URL::route('admin.users.restore', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Restore"
                            data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> 

                        <a href="#" class="delete btn btn-xs btn-default uppercase margin-top-10"
                            data-href="{{ URL::route('admin.users.destroy', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Delete Permanently"
                            data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                    @else
                        
                        <a href="{{ URL::route('backend.users.login', $row->id) }}" class="btn btn-default btn-xs uppercase margin-top-10"><i class="fa fa-sign-in"></i></a>

                        <a href="{{ URL::route('admin.users.edit', $row->id) }}" class="btn green btn-xs uppercase margin-top-10">Edit</a>

                        @if($row->status == 'pending')
                        <a href="#" class="delete btn btn-xs btn-default uppercase margin-top-10"
                            data-href="{{ URL::route('admin.users.delete', [$row->id, query_vars()]) }}" 
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
            <td>{{ status_ico($row->status) }}</td>
            <td>
            {{ status_ico(@$usermeta->user_status) }}
                {{ user_group($row->group) }}<br>

                @if( @$usermeta->availability && $row->group == 'provider' )
                <small class="uppercase text-muted">{!! profile_status($usermeta->availability, true) !!}</small><br>
                @endif

                <small>
                    @if(@$usermeta->package_expiry && @App\Setting::get_setting('enable_free_trial') == 1 )
                        {!! (@$usermeta->package_amount == 0) ? '<span class="text-danger">Free Trial Account</span>' : '<span class="text-primary">Premium Account</span>' !!}<br>
                        Valid until {{ date('d-M-Y', strtotime($usermeta->package_expiry)) }}<br>

                        <?php $plan = @App\Post::where('post_author', $row->id)->where('post_name', 'subscription_plan')->orderBy('id', 'DESC')->first()->post_status; ?>

                        @if($plan) 
                        Access has {{ status_ico($plan) }}
                        @endif

                    @endif
                </small>
            </td>
            <td>    
                @if(@$usermeta->last_login)
                {{ date_formatted(@$usermeta->last_login) }}<br>
                <small>{{ time_ago(@$usermeta->last_login) }}</small>
                @endif
            </td>
            <td>
                {{ date_formatted($row->created_at) }}<br>
                <small>{{ time_ago($row->created_at) }}</small>
            </td>


        </tr>
        @endforeach

        @if(count($rows) == 0)
        <tfoot>
            <tr>
                <td colspan="8" class="text-center">
                    <h3>No record found!</h3>
                </td>
            </tr>
        </tfoot>
        @endif   
 
    </tbody>
</table>



</div>

    </form>

    {{ $rows->links() }}

@endsection


@section('top_style')
<style>
.img-thumb {
    min-width: 50px;
    max-width: 50px;
}    
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
@include('partials.delete-modal')
@stop