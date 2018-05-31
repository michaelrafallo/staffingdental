@extends('layouts.basic')

@section('content')
<div class="text-center">
    <a href="{{ URL::to('/') }}/../">
    <img src="{{ asset(App\Setting::get_setting('logo') ) }}" alt="logo" class="logo-default"/>                         
    </a>
</div>


<div class="col-md-5 col-sm-5 col-centered margin-top-40">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <i class="icon-user font-red-sunglo"></i>
                <span class="caption-subject bold uppercase"> Sign In</span>
            </div>
        </div>
        <div class="portlet-body">
        
        @include('notification')

        <form class="form-horizontal" role="form" method="post" action="">
        
            {!! csrf_field() !!}
            <input type="hidden" name="op" value="1">   

            <div class="form-group">
                <label class="col-md-2 control-label">Email</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="email" placeholder="Email" value="{{ Input::old('email') }}"> 
                    <!-- START error message -->
                    {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Password</label>
                <div class="col-md-10">
                    <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password') }}">
                    <!-- START error message -->
                    {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <label class="mt-checkbox">
                        <input type="checkbox" name="remember" value="1"> Remember me
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn blue btn-primary btn-block">Sign In</button>
                </div>

                @if( App\Setting::get_setting('socialite_connect') == 1 )
                <div class="col-md-offset-2 col-md-10">

                    <h5 class="text-muted margin-top-20">or signin with</h5>
                    <div class="row">
                        <div class="col-md-6 margin-top-10">

                            <a href="{{ URL::route('socialite.connect', 'facebook') }}" class="btn btn-primary btn-facebook btn-block"><i class="fa fa-facebook"></i> Sign in with Facebook</a>
                        </div>
                        <div class="col-md-6 margin-top-10">    
                            <a href="{{ URL::route('socialite.connect', 'google') }}" class="btn btn-danger btn-google btn-block"><i class="fa fa-google"></i> Sign in with Google</a>   
                        </div>
                    </div>
                </div>
                @endif
                              
            </div>

            <hr>
            <p class="text-center">Become a member? <a href="{{ URL::route('auth.register') }}">Register Now!</a>

            <br>
            <a class="btn btn-link" href="{{ route('auth.forgot-password') }}">
            Forgot Your Password?
            </a>
            </p>
        </form>
        

        </div>
    </div>
</div>
@stop



@section('top_style')
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
@stop




