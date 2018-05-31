@extends('layouts.app')

@section('content')

<div class="row margin-top-20">
    <div class="col-md-12">
    @include('notification')
    </div>



    <div class="col-md-5">

        <h1 class="page-title"><i class="icon icon-pin"></i> My Schedule</h1>

        <p class="well text-justify">NOTE: Select "ON" next to any days and times when you are regularly available to work with offices through Staffing Dental. Offices can book your services in real-time during any time slots you select below. They will expect you to honor this schedule and to keep it updated. If your schedule changes, be sure update this page.</p>
        
        <form method="post" class="form-horizontal form-submit">
            
            {{ csrf_field() }}
            <input type="hidden" name="type" value="schedule">

            <div class="form-body">


    <table class="table table-hover table-striped">
    <?php foreach(get_days() as $day_k => $day_v):
        $schedule = json_decode($info->schedule);
        $adk = @$schedule->$day_k;
        $from = (@$adk->from) ? $adk->from : 480;
        $to = (@$adk->to) ? $adk->to : 1020; 
    ?>
    <tr>
        <td width="1">
            <h5 class="sbold"><?php echo $day_v; ?></h5>
            <input name="schedule[{{ $day_k }}]" type="checkbox" class="make-switch availability" data-size="small" 
            {{ count($adk) ? 'checked' : '' }}>
        </td>
        <td style="vertical-align: inherit;"><i class="fa fa-long-arrow-right"></i></td>
        <td>


        <div class="row">

            <div class="col-md-6 pad-fix">
                <select class="form-control time-from select2" name="schedule[{{ $day_k }}][from]" {{ count($adk) ? '' : 'disabled="disabled"' }}>
                    <?php foreach(get_times() as $time_k => $time_v): ?>
                    <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $from) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
                
            <div class="col-md-6 pad-fix mt-xs-10">
                <select class="form-control time-to select2" name="schedule[{{ $day_k }}][to]" {{ count($adk) ? '' : 'disabled="disabled"' }}>
                    <?php foreach(get_times() as $time_k => $time_v): ?>
                    <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $to) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>



        </td>
    </tr>
<?php endforeach; ?>
    </table>


            </div>


            <div class="form-actions">
                <button type="submit" class="btn btn-primary uppercase pull-right"><i class="fa fa-check"></i> Save Schedule</button>
            </div>

            <div class="clearfix"></div>


        </form>

    </div>

    <div class="col-md-7">

        <h1 class="page-title"><i class="icon icon-calendar"></i> My Calendar</h1>
        <p class="well text-justify">Use this to designate specific times as unavailable. This is useful if you are going on vacation or are booked for a work assignment not through the platform.</p>

        <div class="table-responsive">
            <table class="table table-bordered legend">
                <tr>
                    <td class="text-center">
                        <span class="c-a cal-event"></span> Schedule 
                    </td>
                    <td class="text-center" colspan="2">
                        <span class="c-b cal-event"></span> Available
                    </td>
                    <td class="text-center" colspan="2"><span class="c-c cal-event"></span> Not Available
                    </td>
                    <td class="text-center" colspan="2"><span class="c-d cal-event"></span> Temporary Job 
                    </td>
                    <td class="text-center" colspan="2"><span class="c-e cal-event"></span> Appointment
                    </td>
                </tr>
                <tr>
                    <td class="text-center"><small class="uppercase text-muted">Recurring</small></td>
                    <td class="text-center"><small class="uppercase text-muted">Override</small></td>
                    <td class="text-center"><span class="c-a cal-event"></span></td>
                    <td class="text-center"><small class="uppercase text-muted">Override</small></td>
                    <td class="text-center"><span class="c-a cal-event"></span> <span class="c-b cal-event"></span></td>
                    <td class="text-center"><small class="uppercase text-muted">Block</small></td>
                    <td class="text-center"><span class="c-a cal-event"></span> <span class="c-b cal-event"></span></td>
                    <td class="text-center"><small class="uppercase text-muted">Block</small></td>
                    <td class="text-center"><span class="c-a cal-event"></span> </span> <span class="c-b cal-event"></span></td>
                </tr>
            </table>
        </div>


    
        <div id="schedule" class="has-toolbar margin-top-20"> </div>

    </div>



    </div>

</div>




<div class="modal fade" id="sched" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-calendar-check-o"></i> Exception to Availability</h4>
            </div>

            <form method="post">
            
            {{ csrf_field() }}
            <input type="hidden" name="id" value="">            
            <input type="hidden" name="date_from" value="">
            <input type="hidden" name="date_to" value="">

            <input type="hidden" name="type" value="exception">

            <div class="modal-body"> 

            <p class="date-sched no-margin"></p>

                <div class="row margin-top-10">
                    <div class="col-md-3">
                        <h5>Time Start</h5>
                        <select class="form-control time-from select2" name="time_from">
                            <?php foreach(get_times() as $time_k => $time_v): ?>
                            <option value="<?php echo $time_k; ?>" <?php echo ($time_k == 480) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                        
                    <div class="col-md-3">
                        <h5>Time End</h5>
                        <select class="form-control time-to select2" name="time_to">
                            <?php foreach(get_times() as $time_k => $time_v): ?>
                            <option value="<?php echo $time_k; ?>" <?php echo ($time_k == 1020) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>    

                    <div class="col-md-6">
                    <h5>Availability</h5>
                    <input name="post_type" type="checkbox" class="make-switch" data-size="small"
                        data-off-text="NOT AVAILABLE" 
                        data-on-text="AVAILABLE">
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary uppercase btn-block"><i class="fa fa-check"></i> Save Exception</button>
            </div>

            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />
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
.fc-past { 
    background-color: #EEEEEE;
    color: #ddd;
 }
 .fc button {
    height:auto;
 }
 .fc-content {
    white-space: normal!important;
    font-size: 11px;
    min-height: 25px;

}
 .exception .fc-content,  .schedule .fc-content { 
    font-size: 10px; 
    line-height: 14px;
 }

.closeon { float: left; margin: -4px 5px 0 -5px; }
.pad-fix { padding: 0 0px 0 15px; }

.exception {
    background: #a46bed;
    border: 1px solid #a46bed;    
}
.recurring {
    background: #7d7d7d;
    border: 1px solid #7d7d7d; 
}
.booked {
    background: #ed6b75;
    border: 1px solid #ed6b75;
}
.hired {
    background: #36c6d3;
    border: 1px solid #36c6d3;
}

.recurring .closeon,
.booked .closeon, 
.hired .closeon { 
    display: none; 
}
.cal-event {
    width: 12px;
    height: 12px;
    display: inline-block;
    border-radius: 10px!important;
}
.c-a {
    background: #7d7d7d;    
}
.c-b {
    background: #3a87ad;        
}
.c-c {
    background: #a46bed;        
}

.c-d {
    background: #36c6d3;        
}
.c-e {
    background: #ed6b75;        
}
.legend small { font-size: 80%; }
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>


<script src="{{ asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>

    $(document).ready(function() {

        var input_id        = $('[name="id"]'),
            input_date_from = $('[name="date_from"]'),
            input_date_to   = $('[name="date_to"]'),
            input_time_from = $('[name="time_from"]'),
            input_post_type = $('[name="post_type"]'),
            input_time_to   = $('[name="time_to"]')
            schedule        = $('#schedule'),
            sched           = $('#sched'),
            date_sched      = $('.date-sched');




        schedule.fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            defaultDate: '{{ Input::get('date', date('Y-m-d')) }}',
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectHelper: false,
    viewRender: function(currentView){

        var minDate = moment();
        var maxDate = moment().add(2,'weeks');
        // Past
        if (minDate >= currentView.start && minDate <= currentView.end) {
            currentView.calendar.header.disableButton('prev');
        } else {
            currentView.calendar.header.enableButton('prev');
        }

    },
    eventOverlap: function(stillEvent, movingEvent) {
        return stillEvent.allDay && movingEvent.allDay;
    },
       eventRender: function(event, element, view) {
            element.prepend( "<a class='btn btn-xs btn-danger uppercase closeon'><i class='fa fa-remove'></i></a>" );
            element.find(".closeon").click(function() {

                $.get('?delete='+event._id);
               schedule.fullCalendar('removeEvents',event._id);        


            });

            if( event.className == 'booked' || event.className == 'hired' || event.className == 'recurring' ) {

               event.editable = false;
            }  

        },
    eventClick: function(calEvent, jsEvent, view) {

        if( ! $(this).hasClass('booked') && ! $(this).hasClass('hired') && ! $(this).hasClass('recurring') ) {
            var cname = calEvent.className.toString();

            var classname = cname.split(',');

           input_post_val = (classname[1] == 'schedule') ? true : false;

            input_post_type.bootstrapSwitch('state', input_post_val);



            var time = classname[0].split('_');

            sched.modal('show');

            input_id.val( calEvent.id );

            end = $.fullCalendar.moment(calEvent.end).subtract(1, "days").format("YYYY-MM-DD");

            input_date_from.val( calEvent.start.format() );
            input_date_to.val( end );

            input_time_from.val(time[0]).trigger('change');
            input_time_to.val(time[1]).trigger('change');


            lbl_start = $.fullCalendar.moment(calEvent.start).format("dddd, MMMM DD, YYYY"); 
            lbl_end =  $.fullCalendar.moment(end).format("dddd, MMMM DD, YYYY"); 
            date_sched.html('<b>Date Start</b> : '+lbl_start+'<br> <b>Date End</b> : '+lbl_end+'</b>');
        }

    },

    eventResize: function(event, delta, revertFunc) {

var cname = event.className.toString();

var classname = cname.split(',');

 var time = classname[0].split('_');





end = $.fullCalendar.moment(event.end).subtract(1, "days").format("YYYY-MM-DD");

data = { 
    _token : $('meta[name="csrf-token"]').attr('content'),
    id : event.id,
    date_from : event.start.format(),
    date_to: end,
    type: 'exception',
    post_type : $(this).hasClass('schedule') ? 1 : 0,
    time_from : time[0],
    time_to : time[1]
 };


$.post('', data, function(res){

});


    },
    eventDrop: function(event, delta, revertFunc) {

var cname = event.className.toString();
 
var classname = cname.split(',');

 var time = classname[0].split('_');



end = $.fullCalendar.moment(event.end).subtract(1, "days").format("YYYY-MM-DD");

data = { 
    _token : $('meta[name="csrf-token"]').attr('content'),
    id : event.id,
    date_from : event.start.format(),
    date_to: end,
    type: 'exception',
    post_type : $(this).hasClass('schedule') ? 1 : 0,
    time_from : time[0],
    time_to : time[1]
 };


$.post('', data, function(res){

});


    },
            select: function(start, end) {

            input_post_type.bootstrapSwitch('state', false);

                if( ! $(this).hasClass('booked') || ! $(this).hasClass('hired') ) {

/*                    if(start.isBefore(moment()) || IsDateHasEvent(start) || IsDateHasEvent(end) ) {
                        schedule.fullCalendar('unselect');
                        return false;
                    }*/

                    input_id.val('');

                    input_time_from.val(480).trigger('change');
                    input_time_to.val(1020).trigger('change');

                    input_date_from.val( start.format() );
                    input_date_to.val( end.subtract(1, "days").format("YYYY-MM-DD") );

                    lbl_start = $.fullCalendar.moment(start).format("dddd, MMMM DD, YYYY"); 
                    lbl_end =  $.fullCalendar.moment(input_date_to.val()).format("dddd, MMMM DD, YYYY"); 
                    date_sched.html('<b>Date Start</b> : '+lbl_start+'<br> <b>Date End</b> : '+lbl_end+'</b>');

                    sched.modal('show');

                }

            },
            editable: true,
            eventLimit: false, // allow "more" link when too many events
            events: <?php echo $calendar; ?>
        });
        
    });


 // check if this day has an event before
    function IsDateHasEvent(date) {
        var allEvents = [];
        allEvents = schedule.fullCalendar('clientEvents');
        var event = $.grep(allEvents, function (v) {
            return v.start+1 <= date && v.end-1 >= date;
        });
        return event.length > 0;
    }


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

$('.select2').select2({'width':'100%'});

</script>
@stop
