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
                
                </div>    



                @if( view()->exists('auth.register.'.$form) )

                <div class="mt-element-step">

                    <div class="row step-line">
                        <div class="mt-step-desc">

                            @if($sos)
                            <div class="col-md-4 col-xs-4 mt-step-col first step-t-1 {{ active_form(1, $form) }}">
                                <div class="mt-step-number bg-white">1</div>
                                <div class="mt-step-title uppercase font-grey-cascade">Start</div>
                            </div>                            
                            <div class="col-md-4 col-xs-4 mt-step-col step-t-3 {{ active_form(3, $form) }}">
                                <div class="mt-step-number bg-white">2</div>
                                <div class="mt-step-title uppercase font-grey-cascade">Profile</div>
                            </div>
                            <div class="col-md-4 col-xs-4 mt-step-col last step-t-4 {{ active_form(4, $form) }}">
                                <div class="mt-step-number bg-white">3</div>
                                <div class="mt-step-title uppercase font-grey-cascade">Finish</div>
                            </div>
                            @else
                            <div class="col-md-3 col-xs-3 mt-step-col first step-t-1 {{ active_form(1, $form) }}">
                                <div class="mt-step-number bg-white">1</div>
                                <div class="mt-step-title uppercase font-grey-cascade">Start</div>
                            </div>                            
                            <div class="col-md-3 col-xs-3 mt-step-col step-t-2 {{ active_form(2, $form) }}">
                                <div class="mt-step-number bg-white">2</div>
                                <div class="mt-step-title uppercase font-grey-cascade">General Info</div>
                            </div>
                            <div class="col-md-3 col-xs-3 mt-step-col step-t-3 {{ active_form(3, $form) }}">
                                <div class="mt-step-number bg-white">3</div>
                                <div class="mt-step-title uppercase font-grey-cascade">Profile</div>
                            </div>
                            <div class="col-md-3 col-xs-3 mt-step-col last step-t-4 {{ active_form(4, $form) }}">
                                <div class="mt-step-number bg-white">4</div>
                                <div class="mt-step-title uppercase font-grey-cascade">Finish</div>
                            </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-10 col-centered">
                    @include('notification') 
                    </div>

                    <form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
                        {!! csrf_field() !!}       

                        @include('auth.register.'.$form)

                    </form>
                    <hr>
                    <p class="text-center">Already have an account? <a href="{{ URL::route('auth.login') }}">Sign In</a></p>

                    </div>
                </div>

                @else
                   @include('errors.404')
                @endif

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
    $('[name="provider_type"]').hide();
    if (val == 'provider') {
        $('[name="provider_type"]').show();
    }
}); 

$(document).on('submit', 'form', function() {
    $('.btn, button').attr('disabled', 'disabled');        
    $('button').html('Processing ...');        
});


</script>
@stop

