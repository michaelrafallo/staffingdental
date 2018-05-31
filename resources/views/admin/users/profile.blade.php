@extends('layouts.app')

@section('content')

@include('notification')

<div class="row">
    <div class="col-md-7 col-sm-8">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject uppercase">My Profile</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <div class="form-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="op" value="1">   
                        <div class="form-group">
                            <label class="control-label col-md-3">Profile Picture</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"> 
                                        <img src="{{ has_photo($pic) }}">
                                    </div>
                                    <div>
                                        <span class="btn red btn-outline btn-file btn-block btn-sm uppercase">
                                        <span class="fileinput-new"> Select image </span>
                                        <span class="fileinput-exists"> Change </span>
                                        <input type="file" name="file"> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">First Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ $info->firstname }}">
                                    <!-- START error message -->
                                    {!! $errors->first('firstname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Last Name</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ $info->lastname }}">
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
                                        <input type="email" class="form-control" name="email" placeholder="Email"  value="{{ $info->email }}"> 
                                    </div>
                                    <!-- START error message -->
                                    {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                                </div>
                            </div>

                        </div>
                        <div class="form-actions right">
                            <button type="submit" class="btn btn-primary uppercase btn-circle"><i class="fa fa-check"></i> Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5 col-sm-4">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject uppercase">Change Password</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="op" value="2">   
        
                        <div class="form-group">
                            <div class="col-md-12">
                              <label class="control-label">New Password</label>
                                <div class="input-icon right">
                                    <i class="fa fa-key"></i>
                                    <input type="password" class="form-control" name="new_password" placeholder="New Password" value="{{ Input::old('new_password') }}"> </div>
                                    <!-- START error message -->
                                    {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                              <label class="control-label">Confirm New Password</label>
                                <div class="input-icon right">
                                    <i class="fa fa-key"></i>
                                    <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}"> </div>
                                    <!-- START error message -->
                                    {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                                    <!-- END error message -->
                            </div>
                        </div>


                    </div>

                    <div class="form-actions right">
                        <button type="submit" class="btn btn-primary uppercase btn-circle"><i class="fa fa-check"></i> Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


@endsection


@section('top_style')
@stop

@section('bottom_style')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
@stop
