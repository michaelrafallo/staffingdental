@if( App\Setting::get_setting('socialite_connect') == 1 )

    <style>
    .social-connect {
        background: #ededed;
        padding: 10px;
    }
    .btn-facebook, .btn-google {    
        text-align: left;    
    }
    </style>

    <div class="margin-top-20">
        <p class="social-connect">Connect with your social network to authorized login access to your Dental Staffing account.</p>
    </div>

    <h5 class="bold">Connect with : </h5>
    <div class="">
    @if( App\UserMeta::get_meta(Auth::User()->id, 'facebook_user_id') )
        <a href="{{ URL::route('socialite.disconnect', 'facebook') }}" class="btn btn-primary btn-block btn-facebook"><i class="fa fa-facebook"></i> Deauthorized Facebook Login</a>
    @else
        <a href="{{ URL::route('socialite.connect', 'facebook') }}" class="btn btn-primary btn-block btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
    @endif

    </div>
    <div class="margin-top-10">
        @if( App\UserMeta::get_meta(Auth::User()->id, 'google_user_id') )
        <a href="{{ URL::route('socialite.disconnect', 'google') }}" class="btn btn-danger btn-block btn-facebook"><i class="fa fa-google"></i> Deauthorized Google Login</a>    
        @else
        <a href="{{ URL::route('socialite.connect', 'google') }}" class="btn btn-danger btn-block btn-facebook"><i class="fa fa-google"></i> Google</a>    
        @endif
    </div>

@endif