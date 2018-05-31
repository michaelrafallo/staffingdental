@extends('layouts.app')

@section('content')

<div class="row margin-top-20">



    <div class="col-md-12">
    @include('notification')
    </div>

    <div class="col-md-8">
    <div class="portlet-body form">
        <h1 class="page-title"> Schedule</h1>

        <p>NOTE: Select "ON" next to any days and times when you are regularly available to work with offices through Staffing Dental.</p>
        <p>Offices can book your services in real-time during any time slots you select below. They will expect you to honor this schedule and to keep it updated. If your schedule changes, be sure update this page.</p>
        
        <form method="post" class="form-horizontal form-submit">
            
            {{ csrf_field() }}

            <div class="form-body">

                <div class="form-group hide">
                    <div class="col-md-9 col-xs-9">
                        <h4 class="no-margin sbold">Accept same-day booking requests</h4>
                    </div>
                    <div class="col-md-3 col-xs-3 text-right"> 
                        <input name="accept_same_day_appointments" type="checkbox" class="make-switch" data-size="small" 
                        {{ checked($info->accept_same_day_appointments, 'on') }}>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-9 col-xs-9">
                        <h4 class="no-margin">How much lead time would you like from offices?</h4>
                    </div>
                    <div class="col-md-3 col-xs-3 text-right">
                        {!! Form::select('max_booking_days', max_booking_days(), Input::old('max_booking_days', @$info->max_booking_days), ["class" => "form-control"]) !!}
                    </div>
                </div>



    <table class="table table-hover table-striped">
    <?php foreach(get_days() as $day_k => $day_v):
        $availability = json_decode($info->availability);
        $adk = @$availability->$day_k;
    ?>
    <tr>
        <td width="1">
            <h5 class="sbold"><?php echo $day_v; ?></h5>
            <input name="availability[{{ $day_k }}]" type="checkbox" class="make-switch availability" data-size="small" 
            {{ count($adk) ? 'checked' : '' }}>
        </td>
        <td style="vertical-align: inherit;"><i class="fa fa-long-arrow-right"></i></td>
        <td>

        <div class="mt-repeater {{ count($adk) ? '' : 'sched-disabled' }}">
            <div data-repeater-list="availability[{{ $day_k }}]">

                @if(count($adk))
                @foreach($adk as $dk)
                <div data-repeater-item class="mt-repeater-item">
                    <div class="row mt-repeater-row">

                        <div class="col-md-6 pad-fix">
                            <select class="form-control time-from select2" name="business_hours[][from]" {{ count($adk) ? '' : 'disabled="disabled"' }}>
                                <?php foreach(get_times() as $time_k => $time_v): ?>
                                <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $dk->from) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                            
                        <div class="col-md-6 pad-fix mt-xs-10">
                            <select class="form-control time-to select2" name="business_hours[][to]" {{ count($adk) ? '' : 'disabled="disabled"' }}>
                                <?php foreach(get_times() as $time_k => $time_v): ?>
                                <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $dk->to) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

           
                    </div>
                </div>
                @endforeach
                @else
                <div data-repeater-item class="mt-repeater-item">
                    <div class="row mt-repeater-row">

                        <div class="col-md-6 pad-fix">
                            <select class="form-control time-from select2" name="business_hours[][from]" {{ is_array($adk) ? '' : 'disabled="disabled"' }}>
                                <?php foreach(get_times() as $time_k => $time_v): ?>
                                <option value="<?php echo $time_k; ?>" <?php echo ($time_k == 480) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                            
                        <div class="col-md-6 pad-fix mt-xs-10">
                            <select class="form-control time-to select2" name="business_hours[][to]" {{ is_array($adk) ? '' : 'disabled="disabled"' }}>
                                <?php foreach(get_times() as $time_k => $time_v): ?>
                                <option value="<?php echo $time_k; ?>" <?php echo ($time_k == 495) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
           
                    </div>
                </div>                                
                @endif

            </div>

        </div>


        </td>
    </tr>
<?php endforeach; ?>
    </table>


            </div>


            <div class="form-actions">
                <button type="submit" class="btn btn-primary uppercase pull-right"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>

        </form>

    </div>

</div>


@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.sched-disabled .mt-repeater-add {
    display: none;
}

.mt-repeater .mt-repeater-item {
    border-bottom: 0; 
    padding-bottom: 0;
    margin-bottom: 15px;
}    
.sched-disabled .mt-repeater-row .mt-repeater-delete,
.mt-repeater-item:first-child .mt-repeater-row .mt-repeater-delete {
    display: none;
}

.pad-fix { padding: 0 0px 0 15px; }
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>
$('.availability').on('switchChange.bootstrapSwitch', function (event, state) {
    $(this).closest('tr').find('.mt-repeater').addClass('sched-disabled');
    $(this).closest('tr').find('select').attr('disabled', 'disabled');

    if(state == true) {
        $(this).closest('tr').find('.mt-repeater').removeClass('sched-disabled');
        $(this).closest('tr').find('select').removeAttr('disabled');
    }  
}); 


var FormRepeater = function() {
    return {
        init: function() {
            $(".mt-repeater").each(function() {
                $(this).repeater({
                    show: function() {
                        $(this).closest('tr').find('select').removeAttr('disabled');
                        var index = $(this).closest('tr').find('.mt-repeater-item:last-child').index();
                        var val   = $(this).closest('tr').find('.mt-repeater-item:nth-child('+index+') select.time-to').val();
                        var time_from  = parseInt(val) + 15;
                        var time_to  = time_from + 15;

                        $(this).closest('tr').find('.mt-repeater-item:last .time-from').val( time_from ); 
                        $(this).closest('tr').find('.mt-repeater-item:last .time-to').val( time_to ); 

                        $(this).show('fast');

                    },
                    hide: function(e) {
                        $(this).hide('fast', function(){
                            $(this).remove();
                        });
                    },
                    ready: function(e) {
                    }
                })
            })
        }
    }
}();
jQuery(document).ready(function() {
    FormRepeater.init()
});
</script>
@stop
