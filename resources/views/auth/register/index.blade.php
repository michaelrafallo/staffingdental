@extends('layouts.basic')

@section('content')
<!-- BEGIN : STEPS -->

<div class="text-center">
    <a href="{{ URL::to('/') }}/../">
        <img src="{{ asset(App\Setting::get_setting('logo') ) }}" alt="logo" class="logo-default"/>                                 
    </a>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit">
            <div class="portlet-body">


                <div class="col-md-10 col-centered">

<div class="row">
    <div class="col-md-6">
        <h3><i class="icon icon-user-follow"></i> Register Account </h3>    
    </div>
    <div class="col-md-6 text-right">
        <h5 class="margin-top-30">Required ( <span class="text-danger sbold">*</span> )</h5>        
    </div>
</div>

    <div class="alert alert-info">
        <h4>Questions or concerns?</h4>
        <p>
        Email us at <strong><a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a></strong>.
        </p>
    </div>
                
                </div>    





                <div class="mt-element-step">

                    <div class="col-md-10 col-centered">
                    @include('notification') 
                    </div>



                    <form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
                        {!! csrf_field() !!}       


<!-- START STEP 3-->
<div class="col-md-10 col-centered provider-form">

    <table class="table table-bordered table-striped">
        <tr>
            <td>

                <h3 class="text-center">Tell us who you are</h3>
                <input type="hidden" name="user_type_provider" value="{{ $user_type_provider = Input::old('user_type_provider', 'provider') }}">
                <!-- START STEP 2-->
<div class="col-md-10 col-centered margin-top-40">
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

            <div class="provider_type" style="{{ $user_type_provider == 'provider' ? '' : 'display:none;' }}">
                {!! Form::select('provider_type', provider_type(), Input::old('provider_type'), ["class" => "form-control margin-top-10"]) !!}            
            </div>
        </div>
    </div>

</div>
<!-- END STEP 2 -->
            </td>
        </tr>
        <tr>
            <td>
<h3 class="text-center">Account Details</h3>
    <div class="form-group margin-top-40">
        <label class="col-md-3 control-label">Name <span class="required">*</span></label>

        <div class="col-md-3">
            <input type="text" class="form-control" name="firstname" placeholder="First name" value="{{ Input::old('firstname', @$firstname) }}">
          <!-- START error message -->
          {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
          <!-- END error message -->
        </div>
        <div class="col-md-3 mt-xs-10">
            <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ Input::old('lastname', @$lastname) }}">
          <!-- START error message -->
          {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
          <!-- END error message -->
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Email <span class="required">*</span></label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="email" placeholder="Email" value="{{ Input::old('email', @$email) }}">
            <!-- START error message -->
            {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-3 control-label">Zip Code <span class="required">*</span></label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="zip_code" placeholder="Zip Code" value="{{ Input::old('zip_code', @$zip_code) }}">
            <!-- START error message -->
            {!! $errors->first('zip_code','<span class="help-block text-danger">:message</span>') !!}
            <!-- END error message -->
        </div>
    </div>

    <div class="form-group text-center hide">
        <div class="col-md-6 col-centered">
            <a class="btn btn-toggle" data-target="invitation-code">Add Invitation Code</a> 
        </div>
    </div>

    <?php $invitation = Input::get('invitation'); ?>
    <div  class="invitation-code" style="{{ ((@$invitation_code) ? $invitation_code : $invitation) ? '' : 'display:none;' }}">
        <div class="form-group">
            <label class="col-md-3 control-label">Invitation Code</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="invitation_code" placeholder="Invitation Code" 
                value="{{ (@$invitation_code) ? $invitation_code : $invitation }}">
            </div>
        </div>
    </div>                
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group margin-top-20">
                    <label class="col-md-3 control-label">Password <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password', @$password) }}">
                        <span class="help-inline">Password must contain 1 upper case letter, 1 number and minimum of 6 characters.</span>
                        <!-- START error message -->
                        {!! $errors->first('password','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Repeat Password <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password" value="{{ Input::old('password_confirmation', @$password_confirmation) }}">
                        <!-- START error message -->
                        {!! $errors->first('password_confirmation','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>
                </div>
            </td>
        </tr>


                <tr> 
            <td>
            
            <div class="text-center margin-top-20">
                <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="agree" value="1" {{ checked(1, Input::old('agree')) }}>
                I have read, understand and agree to the Staffing Dental <a href="#terms-of-use" data-toggle="modal">Terms of Use</a> and <a href="#privacy-policy" data-toggle="modal">Privacy Policy.</a> 
                <span></span>
                </label>
                <!-- START error message -->
                {!! $errors->first('agree','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>


            </td>
        </tr>
        <tr>
            <td>
                        <button type="submit" class="btn blue btn-lg pull-right"><i class="fa fa-check"></i> Submit</button>
            </td>
        </tr>
    </table>


</div>
<!-- END STEP 3-->





                    </form>
                    <hr>
                    <p class="text-center">Already have an account? <a href="{{ URL::route('auth.login') }}">Sign In</a></p>

                    </div>
                </div>

   

            </div>
            <!-- END : STEPS -->
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>




<div class="modal fade bs-modal-lg" id="terms-of-use" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Terms of Use</h4>
            </div>
            <div class="modal-body">
                <div class="term-content">
                @include('partials.terms.terms-of-use')    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-modal-lg" id="privacy-policy" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Privacy Policy</h4>
            </div>
            <div class="modal-body">
                <div class="term-content">
                @include('partials.terms.privacy-policy')    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@endsection


@section('top_style')

<!-- BEGIN THEME GLOBAL STYLES -->
<link href="{{ asset('assets/global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
<link href="{{ asset('assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->

<!-- BEGIN THEME LAYOUT STYLES -->
<link href="{{ asset('assets/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/layouts/layout/css/themes/darkblue.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
<link href="{{ asset('assets/layouts/layout/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->


<style>
.col-centered {
    float: none;
    margin: 0 auto;
}
.select-type {
    padding: 20px;
}
.select-type.active {
    background: #f1f1f1;
    border: 1px solid #65b4e8;
    box-shadow: inset 0 0 10px 2px #9E9E9E;
}
.select-type:hover {
    cursor: pointer;
}
.select-type .text {
    max-width: 160px;
    padding-top: 10px;
    margin: 0 auto;
    display: block;
}
.select-type:not(.active) img {
    -webkit-filter: grayscale(100%);
    /* Safari 6.0 - 9.0 */
    
    filter: grayscale(100%);
}
.mt-element-step .step-line .active .mt-step-number {
    color: #ffffff!important;
    background: #32c5d2!important;
}
.mt-repeater .mt-repeater-item {
    border: none;
    margin: 0;
}


.term-content .text-indent { text-indent: 35px; }  
.term-content ul li { margin-bottom: 10px; }
.term-content {
    height: 300px;
    overflow: auto;
    padding: 20px;
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>    


<script src="{{ asset('assets/global/plugins/fuelux/js/spinner.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}" type="text/javascript"></script>  
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
@stop

@section('bottom_script')
<script>

$(".touchspin").TouchSpin({
    initval: 40,
    min: 1,
    mousewheel : false
});



$.validate({
  modules : 'security, file',
});





$(document).on('click', '.btn-toggle', function() {
    var target = $(this).data('target');
    $('.' + target).toggle();
});

$(document).on('click', '.select-type', function() {
    var val = $(this).data('val');
    $('.select-type').removeClass('active');
    $(this).addClass('active');
    $('[name="user_type_provider"]').val(val);
    $('.provider_type').hide();
    if (val == 'provider') {
        $('.provider_type').show();
    }
}); 

$(document).on('submit', 'form', function() {
    $('.btn, button').attr('disabled', 'disabled');        
    $('button').html('Processing ...');        
});


</script>
@stop

