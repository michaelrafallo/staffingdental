@extends('layouts.basic')

@section('content')
<div class="col-md-8 col-centered step-4">

    <div class="alert alert-success">
        <p class="text-justify">Thank you for registering! Below is your profile. Please review and update your information, so our practice owners have everything they need to book appointments with you.</p>
    </div>

    <h3 class="sbold">Welcome to Staffing Dental</h3>

    <p class="text-justify">This introductory video is required for all new providers. Once the video ends, you will be able to access your account. If viewing on a mobile device, please click the video's play icon to launch the video, as it may not launch on its own. Please send us a note at <a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a> if you encounter any problems.</p>

    <iframe style="width:100%;" height="480" src="https://www.youtube.com/embed/HsiXO18h3mQ" frameborder="0" allowfullscreen></iframe>

    <div class="text-center margin-top-20">
        <a href="{{ URL::route('provider.accounts.profile') }}" class="btn btn-lg btn-primary">Watch Later</a>    
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




