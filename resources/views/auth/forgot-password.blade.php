@extends('layouts.basic')

@section('content')
<div class="text-center">
    <a href="{{ URL::to('/') }}/../">
    <img src="{{ asset(App\Setting::get_setting('logo') ) }}" alt="logo" class="logo-default"/>                         
    </a>
</div>


<div class="col-md-5 col-sm-5 col-centered margin-top-40">
    <div class="portlet light bordered">

        <div class="portlet-body">



  <form class="forget-form" action="" method="post">
      {{ csrf_field() }}

        @include('notification')

        @if($token)
          <div class="alert alert-info">
            <strong>Note:</strong> You must complete this last step to access your account.
          </div>

    <div class="form-group">
          <label for="new_password" class="sr-only">New Password</label>
      <div class="input-icon">
        <i class="fa fa-lock"></i>
          <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password" value="{{ Input::old('new_password') }}">
          </div>
          <!-- START error message -->
          {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
          <!-- END error message -->
    </div>

      <div class="form-group">
        <label for="new_password_confirmation" class="sr-only">Confirm New Password</label>
      <div class="input-icon">
        <i class="fa fa-lock"></i>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}">
        </div>
        <!-- START error message -->
        {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
        <!-- END error message -->
      </div>


    <div class="form-actions">
      <a href="{{ URL::route('auth.login') }}"class="btn btn-default"><i class="m-icon-swapleft"></i> Back </a>

      <button type="submit" class="btn blue pull-right">
      Change Password <i class="m-icon-swapright m-icon-white"></i>
      </button>
    </div>

        @else


    <h3>Forget Password ?</h3>
    <p>We will find your account.</p>

    <div class="form-group">
      <div class="input-icon">
        <i class="fa fa-envelope"></i>
        <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Email" value="{{ Input::old('email') }}">
      </div>
      <!-- START error message -->
      {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
      <!-- END error message -->
    </div>

    <div class="form-actions">
      <a href="{{ URL::route('auth.login') }}" class="btn btn-default"><i class="m-icon-swapleft"></i> Back </a>

      <button type="submit" class="btn blue pull-right">
      Submit <i class="m-icon-swapright m-icon-white"></i>
      </button>
    </div>


        @endif
          <input type="hidden" name="op" value="1">

      </form>


</div>
</div>
</div>



@endsection
