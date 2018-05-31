@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <i class="fa fa-search"></i> Find Dental Professionals</h1>
<!-- END PAGE TITLE-->    

<form method="get" class="form-horizontal">
<input type="hidden" name="filter" value="{{ Input::get('filter') }}">

<div class="form-body">
    <div class="form-group">
        <div class="col-md-3">
            <label class="uppercase small text-muted">Provider Type</label>
            {!! Form::select('provider_type', ['' => 'All'] + provider_type(), Input::get('provider_type'), ["class" => "form-control"]) !!}

        </div>      
        <div class="col-md-3">
            <label class="uppercase small text-muted">Placement</label>
            {!! Form::select('placement', ['' => 'All'] + placement(), Input::get('placement'), ["class" => "form-control"]) !!}

        </div> 

        <div class="col-md-2">
            <label class="uppercase small text-muted">Sort By</label>
            {!! Form::select('sort_by', sort_by(), Input::get('sort_by'), ["class" => "form-control"]) !!}
        </div>  
        <div class="col-md-2">
            <label class="uppercase small text-muted">Order By</label>
            {!! Form::select('order_by', order_by(), Input::get('order_by'), ["class" => "form-control"]) !!}
        </div>

        <div class="col-md-2">
            <label class="uppercase small text-muted">Per Page</label>
            {!! Form::select('rows', [15 => 15, 25 => 25, 50 => 50, 100 => 100], Input::get('rows'), ["class" => "select2 form-control"]) !!}
        </div>
    </div>

    <div class="form-filter">
        <div class="form-group">
            <div class="col-md-3">
                <label class="uppercase small text-muted">Speciality Type</label>
                {!! Form::select('special_type', ['' => 'All'] + special_type(), Input::get('special_type'), ["class" => "form-control"]) !!}
                </div>

                <div class="col-md-3">
                <label class="uppercase small text-muted">Within</label>
                <div class="input-group">
                    <input type="text" name="miles" class="form-control" value="{{ Input::get('miles', 100) }}">
                    <span class="input-group-addon">
                        miles
                    </span>
                </div>
            </div>
            <div class="col-md-2">
                <label class="uppercase small text-muted">Zip Code</label>
                <input type="text" name="zip_code" class="form-control" value="{{ Input::get('zip_code', $info->zip_code) }}">
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-search"></i> Search</button>
        <a href="{{ URL::route('owner.dentalpro.index') }}" class="btn btn-default">Clear Search</a>

        </div>      
    </div>

</div>
</form>


<div class="well">    
<span class="h4">{{ $count }}</span> dental professional{{ is_plural(count($rows)) }} found.  

@if( $info->get_meta($info->id, 'package_amountx') == 0)
<a href="#owner-trial-period" class="btn-img hide" data-toggle="modal"></a>
@endif

<a href="{{ URL::route('owner.job-postings.add') }}">
Post a Job</a> to engage a wider audience.
</div>

@if( count($rows) == 0)
<h3>No results found</h3>

@if( $info->lat && $info->lng )
    <p>Try broadening your search criteria.</p> 
@else
    <div class="alert alert-danger"><h4>Your location could not be determined!</h4> Please make sure you give us your correct address.
    <a href="{{ URL::route('owner.accounts.settings', ['tab' => 5]) }}" class="sbold">Edit My Address</a></div> 
@endif

@endif


@include('notification')


<?php foreach($rows as $row): 
    $usermeta = get_meta($row->userMetas()->get());

    $selects = array('overall');
    $review_count = App\Post::search([], $selects, [])->where('post_title', $row->id)->where('post_type', 'review');
    $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
    $overall_reviews = $review_count->get()->SUM('overall') / $all_reviews_count;

    $appointments = $post->where('post_title', $row->id)
                        ->where('post_type', 'booking')
                        ->where('post_status', 'completed')
                        ->count();
?>

<div class="result select-disabled">
    <div class="row">    

    <div class="col-md-7">

        <div class="row">

        <div class="col-md-5">
            <img src="{{ has_photo(@$usermeta->profile_picture) }}" class="fullwidth img-thumbnail"> 

            <div class="text-center margin-top-10 btn-group btn-group-justified">
                
                @if( in_array(Auth::User()->status, ['actived', 'approved']) )

                    <a href="#message" class="btn btn-primary btn-sm uppercase send-msg" data-toggle="modal" 
                    data-url="{{ URL::route('messages.sent', ['owner', $row->id]) }}"
                    data-fullname="{{ $row->fullname }}">Message</a>


                @else
                <a href="#owner-pending" class="btn btn-primary btn-sm uppercase send-msg" data-toggle="modal">Message</a>
                @endif

                <a href="{{ URL::route('owner.dentalpro.profile', $row->id) }}" class="btn btn-success btn-sm uppercase">Profile</a>                


            </div>
            
            <p class="uppercase small text-center">Last Login : {{ time_ago($usermeta->last_login) }}</p>

            <div class="margin-top-10"></div>

        </div>
        <div class="col-md-7">
            <div class="pull-right">{{ badge_level($row->id) }}</div>
            
            <h3 class="no-margin">{!! name_formatted($row->id, 'f l') !!}</h3>

            @if($usermeta->provider_type)
            {{ provider_type($usermeta->provider_type) }}
            @endif

            @if(@$usermeta->special_type)
            <small class="text-muted">{{ special_type($usermeta->special_type) }}</small><br>
            @endif

            
            <h5 class="text-warning">
            {{ stars_review($overall_reviews) }}       
            </h5>

            <table class="table table-bordered">
                <tr>
                    <td>

                      <span class="uppercase small text-muted">Acceptance Rate </span><br> 
                        <span class="sbold small">   
                        <?php 
                        $bookings = App\Post::where('post_title', $row->id)->where('post_type', 'booking')->count();
                        $completed = App\Post::where('post_title', $row->id)->where('post_type', 'booking')->where('post_status', 'completed')->count();                        
                        ?>   
                        {{ acceptance_rate($bookings, $completed) }}

                        </span>

                    </td>
                    <td>
                        <span class="uppercase small text-muted">Within</span><br> 
                        <span class="sbold small">{{ get_distance($row->distance) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="uppercase small text-muted">LICENSE</span><br> 
                        <span class="sbold small uppercase">
                            {!! status_ico(@$usermeta->user_status) !!}
                        </span>
                    </td>
                    <td>
                        <span class="uppercase small text-muted">Completed Appointments</span><br> 
                        <span class="sbold small">{{ number_format($appointments) }}</span>

                    </td>
                </tr>
            </table>

            <div class="well">
                <h4 class="no-margin sbold"><i class="fa fa-dollar"></i> {{ @$usermeta->minimum_fee }} / hr
                @if( ! @$usermeta->minimum_fee )
                <small class="text-muted pull-right">Hourly rate not set</small>
                @endif
                </h4> 
            </div>

        </div>

        
        <div class="col-md-12">            
        
            @if(@$usermeta->student == 'yes')
            <div class="well">I am a <b>student</b> currently pursuing a degree in the dental industry at <b>{{ $usermeta->dental_school_name }}</b>
            
            @if( @$usermeta->graduation_year )
            <h4>Graduation year: {{ $usermeta->graduation_year }}</h4>
            @endif
            
            </div>
            @endif

            @if( @$usermeta->background_description )
            <p>{{ str_limit($usermeta->background_description, 170) }}</p>           
            <a href="{{ URL::route('owner.dentalpro.profile', $row->id) }}" class="btn green-sharp btn-outline sbold btn-xs uppercase">READ MORE</a>
            @endif
            
        </div>
            
        </div>


    </div>


        <div class="col-md-5">

        <h5>Select Available schedule below:</h5>

            <div class="m-scroll">
            <div class="h-scroll">




            <?php
            $availables = $unavailables = $schedule = $c_schedules = $cs = $acs  = array();

            /* START AVAILABLE SCHEDULE */
            $ex_availables = $post->where('post_type', 'exception_available')->where('post_author', $row->id);
            foreach ($ex_availables->get() as $ex_available) {
                $ex_a = json_decode($ex_available->post_content);
                $sdates = get_dates_from_range($ex_a->date_from, $ex_a->date_to);
                foreach ($sdates as $sdate) {
                   $availables[$sdate] = $ex_a;   
                }
            }
            /* END AVAILABLE SCHEDULE */

            /* START UNAVAILABLE SCHEDULE */
            $ex_unavailables = $post->where('post_type', 'exception_unavailable')->where('post_author', $row->id);
            foreach ($ex_unavailables->get() as $ex_unavailable) {
                $ex_u = json_decode($ex_unavailable->post_content);
                $sdates = get_dates_from_range($ex_u->date_from, $ex_u->date_to);
                foreach ($sdates as $sdate) {
                   $unavailables[$sdate] = $ex_u;   
                }
            }

            $unavailables = array_keys($unavailables);
            /* END UNAVAILABLE SCHEDULE */

            /* START JOB APPLICATION */
            $applications = $post->where('post_type', 'application')
                               ->where('post_author', $row->id)
                               ->where('post_status', 'hired')
                               ->get();
    
            foreach ($applications as $application) {
                $c_schedules = json_decode($post->get_meta($application->parent, 'schedule'), true);
                if( $c_schedules ) {
                    $cs[] = array_pluck($c_schedules, 'date');
                }
            }   

            $acs = array_flatten($cs);
            /* END JOB APPLICATION */

            ?>


            <?php 
            $schedules = @$usermeta->schedule ? json_decode($usermeta->schedule, true) : array();

            foreach(  get_dates_from_range(date('Y-m-d'), date('Y-m-d', strtotime('+11 days')) ) as $date): 

                $d = date('N', strtotime($date));
                $date = date('Y-m-d', strtotime($date));

                $total_time=$total_minutes='';

                /* START SCHEDULE */
                $schedule = @$schedules[$d];

                $total_minutes =  $schedule['to'] - $schedule['from'];

                $hours = intval($total_minutes / 60);  
                $mins = $total_minutes % 60;   

                $total_time = ($hours) ? $hours.'H ' : '';
                $total_time .= ($mins) ? $mins.'M ' : '';
                /* END SCHEDULE */


                /* START AVAILABLE SCHEDULE */
                $time_from = @$availables[$date]->time_from;
                $time_to   = @$availables[$date]->time_to;

                if( $time_from) {
                    $total_minutes =  $time_to - $time_from;

                    $hours = intval($total_minutes / 60);  
                    $mins = $total_minutes % 60;   

                    $total_time = ($hours) ? $hours.'H ' : '';
                    $total_time .= ($mins) ? $mins.'M ' : '';                
                }
                /* END AVAILABLE SCHEDULE */




                /* START BOOKING */
                $booked = $post->where('post_status', 'confirmed')
                               ->where('post_type', 'booking')
                               ->where('post_name', $date)
                               ->where('post_title', $row->id)
                               ->exists();
                /* END BOOKING */ 
                ?>                


            <div class="col-md-4 col-sm-12 sched-box {{ ($total_time && !$booked && ! in_array($date, $acs) && !in_array($date, $unavailables) ) ? 'available' : '' }}" data-uid="{{ $row->id }}" data-date="{{ $date }}">

                <p class="text-center">
     
                <strong>{{  date('D', strtotime($date)) }}</strong> - 
                <span>{{ date('M', strtotime($date))}}</span> 
                {{  date('d', strtotime($date)) }}<br>
                
     
                @if($total_time)
                    @if( ! $booked && ! in_array($date, $acs) && !in_array($date, $unavailables) )
                   {{ $total_time }}                  
                    @else

                    @if( in_array($date, $unavailables)  )
                              <i class="fa fa-calendar-minus-o"></i>

                    @else
                     <i class="fa fa-calendar-check-o"></i>

                    @endif


                    @endif

                @else
                <i class="fa fa-ellipsis-h"></i>

                    @endif

    
                </p>

            </div>
            <?php endforeach; ?>
            </div>
            </div>

        </div>

    </div>



</div>
<?php endforeach; ?>

<?php parse_str(query_vars(), $appends); ?>
{{ $rows->appends($appends) }}


<div class="modal fade in" id="book" tabindex="-1" role="basic" aria-hidden="true" data-uid="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

            </div>

            <div class="modal-body"></div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@endsection



@section('top_style')

<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.select2-selection {
  min-height: 34px;
  padding: 3px;
  border: 1px solid #c2cad8!important;
}
.select2-selection__arrow {
  padding: 15px;
}

.sched-box {
    border: 1px solid #d6d4d5;
    font-size: 12px;
}
.available {
    background: #ffefae;                
}
.result {
    border: 1px solid #d6d4d5;
    margin-bottom: 25px;
    padding: 20px;
}
.result:hover {
    background: #fbfbfb;    
}
.sched-box.available:hover {
    cursor: pointer;
    background: #337ab7!important;
    color: #fff;
}
.col-bordered {
    border-top: 1px solid #c1c1c1;
    border-left: 1px solid #c1c1c1;
    margin: 5px;
}
.col-cell { 
    border-bottom: 1px solid #c1c1c1;
    border-right: 1px solid #c1c1c1;
    padding: 10px 0 10px 0;
    text-align: center;
    cursor: pointer;
}
.col-cell:hover {
    background: #36c6d3!important;
    color: #fff;
}
.col-cell.selected {
    background: #337ab7!important;
    color: #fff;
}
.col-cell.actived {
    background: #32b0d9;
    color: #fff;
}
</style>


@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>    

@stop

@section('bottom_script')
<script>


$(document).on('click', '.sched-box.available', function(e) {
    e.preventDefault();
    var uid = $(this).data('uid'), date = $(this).data('date');
    $('#book').attr('data-uid', uid);

    @if( in_array(Auth::User()->status, ['actived', 'approved']) )
        $.get('{{ URL::route('dentalpro.book') }}', { uid:uid, date:date }, function(view) {
            $('#book').modal('show');
            $('#book .modal-body').html(view);
        });
    @else
        $('#owner-pending').modal('show');
    @endif

});

$(document).on('click', '.book-now', function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: '{{ URL::route('dentalpro.book.now') }}',
        data: $('#book form').serialize(),
        success: function (res) {
            console.log(res);
          $('.book-step').remove();
          $('.step-3').show();
        }
    });
});  
     
$(document).on('click', '.move-step', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $('.book-step').hide();
    $('.'+target).show();    
    $('[name=breaktime]').val('none').trigger('change');
});   

$(document).on('change', '[name="breaktime"]', function(){

    var mode = $('.book-mode'), 
        uid = $('#book').data('uid'),
        count = $('.col-cell.selected').length,
        id = $(this).data('id'),
        desc = $('.form-desc'),
        confirm = $('.form-confirm'),
        start = $('.col-cell.start'),
        end = $('.col-cell.end'),
        startId = $('.col-cell.start').attr('data-id'),
        endId = $('.col-cell.end').attr('data-id'),
        breaktime = $('[name="breaktime"]');

        $.get('{{ URL::route('dentalpro.get-total') }}', 
            { start : startId, end : $('.col-cell.end').attr('data-id'), uid : uid, breaktime : breaktime.val() },
            function(res) {
            res = JSON.parse(res);
            var st = start.html(), et = $('.col-cell.end').html(), endId = $('.col-cell.end').attr('data-id');

            $('.book-total').html(res.hours+' ('+res.amount+')');
            $('.total-time').html(res.hours);
            $('.total-amount').html(res.amount);
            $('.start-time').val(startId).html(st);
            $('.end-time').val(endId).html(et);
        });

});

$(document).on('click', '.col-cell', function(){
    var mode = $('.book-mode'), 
        uid = $('#book').data('uid'),
        count = $('.col-cell.selected').length,
        id = $(this).data('id'),
        desc = $('.form-desc'),
        confirm = $('.form-confirm'),
        start = $('.col-cell.start'),
        end = $('.col-cell.end'),
        startId = $('.col-cell.start').attr('data-id'),
        endId = $('.col-cell.end').attr('data-id'),
        breaktime = $('[name="breaktime"]');
       
    if(count == 0) {
        mode.text('end');
        $(this).addClass('start');
    } else if(count == 1) {
        $(this).addClass('end');
        mode.text('start');
        desc.hide();
        confirm.show();

        $('.col-cell').each(function(){
            var cid = $(this).attr('data-id');
            if( parseInt(cid) > parseInt(startId) && parseInt($('.col-cell.end').attr('data-id')) > parseInt(cid)) {
                $(this).addClass('actived');
            }
        });
console.log(breaktime.val());
        $.get('{{ URL::route('dentalpro.get-total') }}', 
            { start : startId, end : $('.col-cell.end').attr('data-id'), uid : uid, breaktime : breaktime.val() },
            function(res) {
            res = JSON.parse(res);
            var st = start.html(), et = $('.col-cell.end').html(), endId = $('.col-cell.end').attr('data-id');

            $('.book-total').html(res.hours+' ('+res.amount+')');
            $('.total-time').html(res.hours);
            $('.total-amount').html(res.amount);
            $('.start-time').val(startId).html(st);
            $('.end-time').val(endId).html(et);
        });

    } else {
        desc.show();
        confirm.hide();
        $('.col-cell').removeClass('actived selected start end');
        $(this).addClass('start');
    }

    if( parseInt(startId) >= parseInt($(this).attr('data-id')) ) {
        desc.show();
        confirm.hide();
        $('.col-cell').removeClass('actived selected start end');
         $(this).addClass('start');
    }

    $(this).addClass('selected');

});



    $(document).on('click', '.btn-filter', function(e) {
    e.preventDefault();

    if( $('[name=filter]').val() == 'show' ) {
        $('.form-filter').hide();                
        $('.btn-filter span').text('Show');
        $('[name=filter]').val('hide');
    } else {
        $('.form-filter').show();        
        $('.btn-filter span').text('Hide');
        $('[name=filter]').val('show');
    }
});

$(".datepicker").datepicker();

$(".select2").select2({width: '100%'});
</script>
@stop
