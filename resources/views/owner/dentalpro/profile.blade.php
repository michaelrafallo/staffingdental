@extends('layouts.app')

@section('content')
<div class="profile">
    <div class="tabbable-line tabbable-full-width">
        <div class="tab-content">

            @include('notification')

            <div class="tab-pane active" id="tab_1_1">


                    <a href="{{ URL::route('owner.dentalpro.index') }}" class="btn uppercase pull-right"><i class="fa fa-long-arrow-left"></i> Find More Talents</a> 

                @if($info->status == 'pending')
                <p><i class="{{ ($info->status == 'approved') ? 'online' : 'offline' }}"></i> <strong>Profile status:</strong> 
                <span class="text-muted">
                Not yet live, and is pending admin review
                </p>
                @endif

                <div class="row">
                    <div class="col-md-3">

                        <ul class="list-unstyled profile-nav">
                            <li>
                               <img src="{{ has_photo($info->profile_picture) }}" class="img-thumbnail">
                            </li>



                            @if( in_array($info->status, ['approved', 'actived']) ) 
                                @if( in_array(Auth::User()->status, ['approved', 'actived']) )

                                    <li><a href="{{ URL::route('messages.view', ['owner', $info->id]) }}">Go to messenger</a></li>                                

                                @else
                                    <li><a href="#owner-pending" data-toggle="modal">Go to messenger</a></li>   
                                @endif
                            @endif


                        </ul>

                        <div class="text-center">

                            @if($info->is_favorite($info->id))
                            <a href="{{ URL::route('owner.dentalpro.favorites', [$info->id, 'action' => 'remove']) }}" class="btn btn-danger btn-sm btn-block  uppercase"><i class="fa fa-user-times"></i> Remove to Favorite</a>
                            @else                                
                            <a href="{{ URL::route('owner.dentalpro.favorites', [$info->id, 'action' => 'add']) }}" class="btn btn-success btn-sm btn-block uppercase"><i class="fa fa-user-plus"></i> Add to Favorite</a>
                            @endif

                            @if( in_array($info->status, ['approved', 'actived']) ) 
                                
                                @if( in_array(Auth::User()->status, ['approved', 'actived']) )

                                <a href="#message" class="btn btn-primary btn-sm btn-block uppercase send-msg" data-toggle="modal" href="#message" 
                                data-url="{{ URL::route('messages.sent', ['owner', $info->id]) }}"
                                data-fullname="{{ $info->fullname }}">Message</a>

                                @else
                                <a href="#owner-pending" class="btn btn-primary btn-block btn-sm uppercase send-msg" data-toggle="modal">Message</a>
                                @endif

                            @endif


                            <div class="text-warning margin-top-10 h4">
                                {{ stars_review($overall_reviews) }}           
                            </div>
                            <small class="uppercase">{{ $all_reviews_count }} review{{ is_plural($all_reviews_count) }}</small>
                        </div>

                        @if( $info->languages )
                        <p>
                            <small class="sbold">LANGUAGES</small><br>
                            <ul>
                            @foreach(json_decode($info->languages) as $language)
                            <li>{{ user_languages($language->user_languages) }} <span class="text-muted">({{ $language->fluency }})</span></li>
                            @endforeach
                            </ul>
                        </p>
                        @endif

                        <p>
                            <small class="sbold">LAST LOGIN</small><br>
                            {{ time_ago($info->last_login) }}
                        </p>
                        
                        @if( badge_level($info->id) )
                        {{ badge_level($info->id) }} Engagement Points
                        @endif
                        
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 profile-info select-disabled">                                
                                <span class="uppercase">{!! profile_status($info->availability, true) !!}</span>

                                <h1 class="font-green sbold uppercase">{!! name_formatted($info->id) !!} </h1>
                                <h4>{{ provider_type($info->provider_type) }}</h4>
                            </div>
                        </div>
                        <div class="row margin-top-20">
                            <div class="col-md-5">
                                <i class="icon icon-pointer pull-left"></i>
                                <small class="uppercase">
                                    <strong>LOCATED IN </strong><br>
                                    {{ $info->city }} {{ $info->state ? states($info->state) : '' }}
                                </small>                               
                            </div>

                            <div class="col-md-4 mt-xs-10 col-xs-6">
                                <i class="icon icon-badge pull-left""></i>
                                <small class="uppercase">
                                PROVIDER LICENSE<br>
                                <strong>{!! status_ico($info->user_status)  !!}</strong>
                                </small>                          
                            </div>
                            <div class="col-md-3 mt-xs-10 col-xs-6">
                                <i class="icon icon-clock pull-left""></i>
                                <small class="uppercase">
                                {{ number_format($appointments) }} Completed<br>
                                <strong class="uppercase">Appointments</strong>
                                </small>
                            </div>
                        </div>

                        <div class="bg-grey margin-top-20">
                            <div class="row">

                                <div class="col-md-2 col-xs-6">                                    
                                    @if( @$info->minimum_fee )
                                    <h3 class="no-margin"><i class="fa fa-dollar"></i> {{ @$info->minimum_fee }}/hr</h3>
                                    @else
                                    <span class="text-muted">Hourly rate<br> not set</span>
                                    @endif
                                </div>

                                <div class="col-md-3 col-xs-6 small">
                                    ACEEPTANCE RATE
                                    <?php 
                                    $all = App\Post::where('post_title', $info->id)->where('post_type', 'booking')->count();
                                    $completed = App\Post::where('post_title', $info->id)->where('post_type', 'booking')->where('post_status', ['completed'])->count();                        
                                    ?>   
                                    <h4 class="no-margin sbold">{{ acceptance_rate($all, $completed) }}</h4>                          
                                </div>


                                <div class="col-md-3 col-xs-6 mt-xs-10 small">
                                    <i class="icon icon-notebook pull-left hidden-xs"></i>
                                    @if($confirmed)
                                        <b>{{ number_format($confirmed) }}</b> ACCEPTED <br> <small>BOOKING REQUEST</small>
                                    @else
                                    <small>NO BOOKING REQUESTS ACCEPTED YET</small>
                                    @endif                            
                                </div>
                                <div class="col-md-4 col-xs-6 mt-xs-10 small">
                                @if( $info->temporary_assignments || $info->permanent_position )
                                    <i class="icon icon-check pull-left hidden-xs"></i>
                                    AVAILABLE FOR<br>
                                    {{ work_status($info->temporary_assignments, $info->permanent_position) }}                               
                                @endif
                                </div>
                            </div>
                        </div>

                        <div class="portlet light bordered margin-top-20">
                            <div class="portlet-title">                                
                                <div class="caption">
                                   Staffing Dental Provider #{{ sprintf('%07d', $info->id) }}

                                    @if($job)
                                    <div class="margin-top-20">
                                    @if( in_array($job->post_status, ['cancelled', 'waiting', 'invited']) )
                                    <a href="#" class="pop-modal btn green-sharp btn-sm btn-outline uppercase"
                                        data-href="{{ URL::route('owner.job-postings.hire', ['invited', $job->id, $job->post_author]) }}" 
                                        data-toggle="modal"
                                        data-target=".ask-modal" 
                                        data-title="Invite <b>{{ $user->fullname }}</b> for interview"
                                        data-val="{{ @App\Setting::get_setting('applicant_invite') }}">Invite</a>

                                    <a href="#" class="pop-modal btn btn-primary btn-sm uppercase"
                                        data-href="{{ URL::route('owner.job-postings.hire', ['hired', $job->id, $job->post_author]) }}" 
                                        data-toggle="modal"
                                        data-target=".ask-modal" 
                                        data-title="Hire <b>{{ $info->fullname }}</b>"
                                        data-val="{{ @App\Setting::get_setting('applicant_hire') }}"><i class="fa fa-check"></i> Hire</a>
                                    @endif

                                    @if( $job->post_status != 'cancelled' )
                                    <a href="#" class="pop-modal btn green-sharp btn-sm btn-outline uppercase"
                                        data-href="{{ URL::route('owner.job-postings.hire', ['cancelled', $job->id, $job->post_author]) }}" 
                                        data-toggle="modal"
                                        data-target=".ask-modal" 
                                        data-title="Cancel Application"
                                        data-val="{{ @App\Setting::get_setting('applicant_cancel') }}">Cancel Application</a>
                                    @endif
                   
                                    <a href="{{ URL::route('owner.job-postings.view', $job_id) }}" class="btn btn-sm uppercase"><i class="fa fa-long-arrow-left"></i> Back to job post</a>
                                    
                                    </div>
                                    @endif

                                </div>
                            </div>
                            <div class="portlet-body">

                                @if($info->resume || $info->government_issued_id || $info->proof_of_insurance)
                                    <h4 class="sbold">Documents</h4>
                                    <ul>
                                        @if($info->resume)
                                        <li><a href="{{ asset($info->resume) }}" target="_blank" class="btn">View Resume</a></li>
                                        @endif

                                        @if($info->proof_of_insurance)
                                        <li><a data-href="{{ asset($info->proof_of_insurance) }}" class="btn-view btn" data-toggle="modal" data-target=".view-modal">View Proof of Malpractice Insurance</a></li>
                                        @endif

                                        @if($info->government_issued_id)        
                                        <li><a data-href="{{ asset($info->government_issued_id) }}" class="btn-view btn" data-toggle="modal" data-target=".view-modal">View Government Issued Pictured ID</a></li>
                                        @endif
                                    </ul>
                                @endif


                                @if( $info->dental_school_name )
                                    <h4 class="sbold">School Information</h4> 
                                    @if(@$info->student == 'yes')
                                    <div class="well">I am a <b>student</b> currently pursuing a degree in the dental industry at <b>{{ $info->dental_school_name }}</b>
                                    <h4>Graduation year: {{ $info->graduation_year }}</h4>
                                    </div>
                                    @else
                                    <p>{{ $info->dental_school_name }} ({{ $info->graduation_year }})</p>                                
                                    @endif
                                @endif
                                
                                @if( $info->background_description )
                                <h4 class="sbold">Background information</h4>
                                <p class="text-justify">{{ $info->background_description }}</p>
                                @endif

                                @if( $info->professional_objectives )
                                <h4 class="sbold margin-top-20">Professional objectives</h4>
                                <p class="text-justify">{{ $info->professional_objectives }}</p>
                                @endif
                                
                                @if($info->skills)
                                <h4 class="sbold margin-top-20 hide">Skills</h4>
                                <ul>
                                    @foreach(json_decode($info->skills) as $skill)
                                    <li>{{ skills($skill) }}</li>
                                    @endforeach
                                </ul>
                                @endif

                                <hr>


                       
                                <?php 
                                $address = array(
                                  @$info['street_address'],
                                  @$info['city'],
                                  @$info['state'],
                                  @$info['zip_code'],
                                  'US'
                                );

                                $address = implode(' ', $address);
                                ?>


                                <h4 class="sbold">Email Address</h4>
                                <p class="text-justify">{{ $info->email }}</p>                                

                                <h4 class="sbold">Contact Information </h4>
                                <i class="fa fa-phone"></i> <a href="tel:{{ $info->phone_number }}">{{ $info->phone_number }}</a><br>
                                <i class="fa fa-map-marker"></i> {{ $info->street_address }} {{ $info->city }} {{ $info->state }} {{ $info->zip_code }}
                                </p>

                                <iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode($address) }}&amp;output=embed"></iframe>
                

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade view-modal" id="viewModal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title">Image</h4>
        </div>
        <div class="modal-body">
        <img src="" class="img-responsive">     
        </div>

        <div class="modal-footer">
        <a href="" class="btn btn-default btn-download" download><i class="fa fa-download" download></i> Download</a>
        <button class="btn btn-default" aria-hidden="true" data-dismiss="modal" class="close" type="button">Cancel</button> 
        </div>
       
    </div>
  </div>
</div>

@if( in_array($info->status, ['approved', 'actived']) ) 
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-clock font-red-sunglo"></i>
            <span class="caption-subject font-red-sunglo bold uppercase">My Schedule</span>
        </div>
    </div>
    <div class="portlet-body">
  <div id="schedule" class="has-toolbar"> </div>
    </div>
</div>

@if( in_array($info->status, ['approved', 'actived']) ) 
@include('partials.review')
@endif
    
<div class="modal fade in" id="book" tabindex="-1" role="basic" aria-hidden="true" data-uid="{{ $info->id }}">
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



<div class="modal fade view-modal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title uppercase">Title goes here ...</h4>
        </div>
        <div class="modal-body">
                 
        </div>

        <div class="modal-footer">
        <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
        <span class="msg"></span>           
        </div>
       
    </div>
  </div>
</div>

<div class="modal fade ask-modal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title uppercase">Title goes here ...</h4>
        </div>

        <form method="post" class="form-horizontal form-submit" action="">
        {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="6"></textarea>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
        <button class="btn btn-default btn-primary uppercase" type="submit">Confirm</button> 
        <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
        </div>

        </form>
      
    </div>
  </div>
</div>
@endif



@endsection



@section('top_style')
<link href="{{ asset('assets/pages/css/profile-2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />

<style>

.icon {
    color: rgba(0,0,0,0.4);
    margin: 13px 10px 0 0;
    font-size: 2.1em;
}

.img-review {
    margin: 13px 10px 0;    
    width: 80px;
}

.bg-grey {
    padding: 15px 20px 15px 20px;
    background-color: #f1f4f7;    
}
.fc-title { 
    font-size: 1.5em;
    text-align: center;
    display: block;
    font-weight: bold;
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
.offline {
    width: 10px;
    height: 10px;
    background: #e43a45;
    display: inline-block;
    border-radius: 20px;
}
.online {
    width: 10px;
    height: 10px;
    background: #26c281;
    display: inline-block;
    border-radius: 20px;
}

@media only screen and (max-width: 990px) {
    .bg-grey div {
        font-size: 12px;
    }
}
.fc-past { 
    background-color: #EEEEEE;
    color: #ddd;
 }
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>


$(document).on('click', '.btn-review', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $('.form-review').hide();
    $('.'+target).show();
});


$(document).ready(function() {
    



    $('#schedule').fullCalendar({
        header: {
            left: 'title',
            center: '',
            right: 'prev,next today'
        },
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: false, // allow "more" link when too many events
        height: 470,
        events: { url: '?feed=json' },
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
        eventRender: function (event, element) {

        element.click(function() {
            var date = event.start.format(), uid = $('#book').data('uid');
        @if( in_array(Auth::User()->status, ['approved', 'actived']) )
            $.get('{{ URL::route('dentalpro.book') }}', { uid:uid, date:date }, function(view) {
                $('#book').modal('show');
                $('#book .modal-body').html(view);
            });
        @else
            $('#owner-pending').modal('show');
        @endif


        });

    }
    });
    
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

        $.get('{{ URL::route('dentalpro.get-total') }}', 
            { start : startId, end : $('.col-cell.end').attr('data-id'), uid : uid },
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

$(document).on('click keyup', '.review', function() {
    var checked = $('input.review:checked').length;
    if( checked == 3 ) {
        $('.submit-review').removeAttr('disabled');            
    } else {
        $('.submit-review').attr('disabled', 'disabled');                        
    }

});


$(document).on('click', '.pop-modal', function() {
    var url = $(this).data('href'), type = $(this).data('type'), val = $(this).data('val');
    
    $('.modal .modal-body textarea').val(val);      

   if(type == 'ajax') {
        $.get(url, function(res) {
            $('.view-modal .modal-body').html(res); 
        });    
   }
      

});

</script>

@include('partials.delete-modal')
@stop
