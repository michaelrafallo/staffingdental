@extends('layouts.app')

@section('content')

<div class="col-md-7 col-sm-8 col-centered margin-top-40">
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject uppercase">Create Admin</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" role="form" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="op" value="1">

                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">First Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ Input::old('firstname') }}">
                            <!-- START error message -->
                            {!! $errors->first('firstname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Last Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ Input::old('lastname') }}">
                            <!-- START error message -->
                            {!! $errors->first('lastname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Email</label>
                        <div class="col-md-8">
                            <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ Input::old('email') }}"> 
                            </div>
                            <!-- START error message -->
                            {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-8">
                            <div class="input-icon right">
                                <i class="fa fa-key"></i>
                                <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password') }}"> 
                            </div>
                            <!-- START error message -->
                            {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>
                </div>

                <div class="form-actions right">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-9">
                            <button type="submit" class="btn btn-primary uppercase btn-circle">Create Account</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection


@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
@stop
