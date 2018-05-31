@extends('layouts.find')

@section('content')
<div class="profile col-md-9 col-centered">
    <div class="tabbable-line tabbable-full-width">
        <div class="tab-content">

            @include('notification')

            <div class="tab-pane active" id="tab_1_1">


                    <a href="{{ URL::route('find.employees.index') }}" class="btn uppercase pull-right"><i class="fa fa-long-arrow-left"></i> Find More Talents</a> 

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
                                       <li><a data-toggle="modal" href="#basic">Go to messenger</a></li>   
                            @endif


                        </ul>

                        <div class="text-center">

      

                            @if( in_array($info->status, ['approved', 'actived']) ) 
                                
                   <a data-toggle="modal" href="#basic" class="btn btn-primary btn-block btn-sm uppercase send-msg">Message</a>

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

                                <h1 class="font-green sbold uppercase">
                                {{ ucwords(@$info->find($info->id)->firstname) }} 
                                <span class="blurry">{{ str_mask(@$info->find($info->id)->lastname, 0) }}</span>
                                 </h1>
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

                                <div class="col-md-4 col-xs-6">
                                    <h3 class="no-margin"><i class="fa fa-dollar"></i> {{ @$info->minimum_fee }}/hr</h3>
                                </div>


                                <div class="col-md-4 col-xs-6 small">
                                    ACEEPTANCE RATE
                                    <?php 
                                    $all = App\Post::where('post_title', $info->id)->where('post_type', 'booking')->count();
                                    $completed = App\Post::where('post_title', $info->id)->where('post_type', 'booking')->where('post_status', ['completed'])->count();                        
                                    ?>   
                                    <h4 class="no-margin sbold">{{ acceptance_rate($all, $completed) }}</h4>                          
                                </div>


                                <div class="col-md-4 col-xs-6 mt-xs-10 small">
                                    <i class="icon icon-notebook pull-left hidden-xs"></i>
                                    @if($confirmed)
                                        <b>{{ number_format($confirmed) }}</b> ACCEPTED <br> BOOKING REQUEST
                                    @else
                                    NO BOOKING REQUESTS<br> ACCEPTED YET
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
                                </div>
                            </div>
                            <div class="portlet-body">

                                @if($info->resume || $info->government_issued_id || $info->proof_of_insurance)
                                    <h4 class="sbold">Documents</h4>
                                    <ul>
                                        @if($info->resume)
                                        <li><a  data-toggle="modal" href="#basic">View Resume</a></li>
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
                                
                                @if( @$info->background_description )
                                <h4 class="sbold">Background information</h4>
                                <p class="text-justify">{{ $info->background_description }}</p>
                                @endif

                                @if( @$info->professional_objectives ) 
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
                                  "US"
                                );

                                $address = implode(' ', $address);
                                ?>


                                <h4 class="sbold">Email Address</h4>
                                <span class="blurry">{{ str_mask($info->email, 0) }}</span>                                

                                <h4 class="sbold">Contact Information </h4>
                                <i class="fa fa-phone"></i> <span class="blurry">{{ str_mask($info->phone_number, 0) }}</span><br>
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
<div class="portlet light bordered col-md-8 col-centered">
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

            $('#basic').modal('show');


        });

    }
    });
    
});     
</script>
<script>
$('.blurry').tooltip({ placement: 'top', title: 'Please login or register to view.' });
</script>
@stop
