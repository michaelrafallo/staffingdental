<?php extract($user_data); ?>

<input type="hidden" name="form" value="3">
<input type="hidden" name="user_type_provider" value="{{ (@$user_type_provider) ? $user_type_provider : 'provider' }}">



<!-- START STEP 2-->
<div class="col-md-8 col-centered step-2">
    <div class="form-group">
        <div class="col-md-6">
            <div class="select-type text-center {{ actived('owner', @$user_type_provider) }}" data-val="owner">
                <img src="{{ asset('img/owner-active-2.png') }}">
                <p class="text sbold">Dental practice seeking dental staff</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="select-type text-center {{ actived('provider', (@$user_type_provider) ? $user_type_provider : 'provider') }}" data-val="provider">
                <img src="{{ asset('img/provider-active-2.png') }}">
                <p class="text sbold">Dental professional seeking employment</p>
            </div>
            {!! Form::select('provider_type', provider_type(), Input::old('provider_type', @$provider_type), ["class" => "form-control margin-top-10"]) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <a href="{{ URL::route('auth.register', 1) }}" class="btn green btn-lg"><i class="fa fa-angle-double-left"></i> Previous</a>
            <button type="submit" class="btn green btn-lg pull-right">Next <i class="fa fa-angle-double-right"></i></button>
        </div>
    </div>
</div>
<!-- END STEP 2 -->
