@yield('bottom_style')

<!--[if lt IE 9]>
<script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
<script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script> 
<script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}"></script> 
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery.pulsate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('assets/pages/scripts/dashboard.min.js') }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>    
<script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script src="{{ asset('js/script.js') }}" type="text/javascript"></script>

    

@yield('bottom_plugin_script')

@yield('bottom_script')

@yield('filter_script')


<script>

$(".pulsate-crazy-target").pulsate({
    color: "#ea5460",
    reach: 20,
    repeat: 10,
    speed: 500,
    glow: !0
});

$(document).on('submit', '.form-submit', function() {
	$('[type=submit]').attr('disabled', 'disabled')
					  .addClass('disabled')
					  .html('<i class="fa fa-gear"></i> Processing ...');
});


$(document).on('click', '.send-msg', function(e) {
e.preventDefault();
    var url = $(this).data('url');
    var fullname = $(this).data('fullname');

    $('#message .fullname').html(fullname);
    $('#message form').attr('action', url);
    $('#message [name=action]').val(url);
    $('#message [name=fullname]').val(fullname);

});

var init_repeater = function() {
    $('.mt-repeater').each(function(){
        $(this).repeater({
            show: function () {
                $(this).slideDown();
               app_init();
            },
            hide: function (deleteElement) {
                if(confirm('Are you sure you want to delete this row?')) {
                    $(this).slideUp(deleteElement);
                }
            },
            ready: function (setIndexes) {

            }

        });
    });
}
init_repeater();


var app_init = function() {
    $('.datepicker').datepicker({
        autoclose: true,
        todayBtn: true,
        format: "yyyy-mm-dd"
    });
    $(".datepicker").inputmask({
          mask: "9999-99-99",
          placeholder: "YYYY-MM-DD",
    }); 

    $(".select2, .select2-multiple").select2({width: '100%'});
}

jQuery(document).ready(function() {    
   app_init();
});


@if( is_array(@$errors->messages()['message']) )
    $('#message').modal('show');
@endif


</script>