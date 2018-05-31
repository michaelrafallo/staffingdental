@extends('layouts.app')

@section('content')

@if( in_array($info->status, ['approved', 'actived']) ) 
<div class="pull-right margin-top-20">
    <input name="visibility" type="checkbox" class="make-switch" 
    data-on-color="success" 
    data-size="small" 
    data-off-text="INVISIBLE" 
    data-on-text="ONLINE"
    data-url="{{ URL::route('provider.accounts.updatemeta') }}"
    {{ checked(@$info->availability, 'online') }}>
</div>
@endif

<p class="profile-status p-invisible" style="{{ (@$info->availability=='invisible') ? '' : 'display:none;'  }}">
<i class="offline"></i> <strong>Profile status:</strong> <span class="text-muted">offline and invisible to dental offices</span>
</p>

<p class="profile-status p-online" style="{{ (@$info->availability=='online') ? '' : 'display:none;'  }}"><i class="{{ (in_array($info->status, ['approved', 'actived'])) ? 'online' : 'offline' }}"></i> <strong>Profile status:</strong> 
<span class="text-muted">
@if($info->status == 'pending')
Invisible and pending for admin review


<div class="alert well">
    <h4 class="no-margin sbold">Your profile needs to be approved</h4>
    <p class="text-justify margin-top-20">Your profile needs to be approved before applying a job. To ensure prompt approval of your profile, <strong>please make sure you have provided all required information</strong>, including your professional license number, hourly rate and background description. If you believe your profile is complete, and more than 24 hours have passed since you completed your profile, please contact customer support at <a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a>. We are here to support you in your career.</p>
</div>


@endif

@if( in_array($info->status, ['approved', 'actived']) ) 
live and visible to dental offices
@endif
</span>

</p>


<div class="profile">
    <div class="tabbable-line tabbable-full-width">

        <div class="tab-content">

            @include('notification')

            <div class="tab-pane active" id="tab_1_1">
                <div class="row">
                    <div class="col-md-3">
            
                        <div class="thumbnail no-margin">
                            <a href="#profile_picture" class="btn btn-block" data-toggle="modal"><i class="fa fa-plus"></i> Upload Profile Picture</a>
                            <img src="{{ has_photo($info->profile_picture) }}">
                        </div>
   
                        <div class="text-center">
                            <a href="{{ URL::route('provider.accounts.settings', ['tab' => 1]) }}" class="btn btn-primary btn-sm btn-block uppercase">Account Settings</a>

                            <div class="text-warning margin-top-10 h4">
                                {{ @stars_review($overall_reviews) }}           
                            </div>
                            <small class="uppercase">{{ $all_reviews_count }} review{{ @is_plural($all_reviews_count) }}</small>

                        </div>


                        @if( $info->languages)
                        <p>
                            <small class="sbold">LANGUAGES</small><br>
                            <ul>
                            @foreach(json_decode($info->languages) as $language)

                                @if($language->user_languages)
                                <li>{{ @user_languages($language->user_languages) }} <span class="text-muted">({{ $language->fluency }})</span></li>
                                @endif

                            @endforeach
                            </ul>
                        </p>
                        @endif

                        <p>
                            <small class="sbold">LAST LOGIN</small><br>
                            {{ @time_ago($info->last_login) }}
                        </p>

  
                            <?php 
                                $profile_count = 10;

                                 if(has_dental_license(@$info->provider_type)) {
                                    $profile_count = 11;
                                 }

                                $complete = 0; 
                            ?>
                            <ul class="list-unstyled profile-nav">

                                <?php
                                $address = array(
                                    @$info->phone_number,
                                    @$info->street_address,
                                    @$info->zip_code,
                                    @$info->state,
                                    @$info->city,
                                );
                                ?>
                                @if( count(array_filter($address)) != 5)
                                <li>
                                    <a href="#complete_address" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Add/Complete Address</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if( ! @$info->background_description)
                                <li>
                                    <a href="#background_description" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Add Background Description</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if(has_dental_license(@$info->provider_type))
                                    @if( ! @$info->dental_license)
                                    <li>
                                        <a href="#dental_license" class="btn-block" data-toggle="modal">
                                        <i class="fa fa-plus"></i> Add Dental License Number</a>                                
                                    </li>
                                    <?php $complete += 1; ?>
                                    @endif
                                @endif

                                @if( ! @$info->minimum_fee)
                                <li>
                                    <a href="#minimum_fee" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Add Fee</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if( ! @$info->graduation_year || ! @$info->dental_school_name )
                                <li>
                                    <a href="#school_info" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Add School Information</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if( ! @$info->availability)
                                <li>
                                    <a href="{{ URL::route('provider.accounts.schedule') }}" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Update Your Schedule</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif


                                @if( ! @$info->profile_picture)
                                <li>
                                    <a href="#profile_picture" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Upload Profile Picture</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if( ! @$info->resume)
                                <li>
                                    <a href="#resume" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Upload Resume</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if( ! $info->government_issued_id)
                                <li>
                                    <a href="#gevernment_issued_id" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Upload Valid Government Issued Picture ID</a>
                                </li>
                                <?php $complete += 1; ?>
                                @endif


                                @if( ! @$info->email_confirmed)
                                <li>
                                    <a href="#email_address" class="btn-block" data-toggle="modal">
                                    <i class="fa fa-plus"></i> Confirm Your Email Address</a>                                
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                                @if( ! @$info->languages) 
                                <li>
                                <a href="{{ URL::route('provider.accounts.settings', ['tab' => 4]) }}"><i class="fa fa-plus"></i> Add Language</a>
                                </li>
                                <?php $complete += 1; ?>
                                @endif

                            </ul>

                        <?php $completeness = @number_format( (($profile_count-$complete)/$profile_count) * 100 ); ?>
                        <div class="progress progress-striped margin-top-10">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: {{ $completeness }}%">
                                <span class="sr-only"> {{ $completeness }}% Complete (success) </span>
                            </div>
                        </div>
                        
                        Profile Completeness: <b class="text-danger">{{ $completeness }}%</b>

                        @include('partials/social-login')
                        
                    </div>


                    <div class="col-md-9">

                        <div class="row margin-top-2">
                            <div class="col-md-12 profile-info">
                                <div class="pull-right">{{ @badge_level($info->id) }}</div>
                                <h1 class="font-green sbold uppercase">{{ $info->fullname }}</h1>                                
                                <h4>{{ @provider_type($info->provider_type) }} (Graduated on year {{ $info->graduation_year }})</h4>
                            </div>
                        </div>

                        <div class="row margin-top-20">
                            <div class="col-md-5">
                                <i class="icon icon-pointer pull-left"></i>
                                <small class="uppercase">
                                    <strong>LOCATED IN </strong><br>
                                    @if($info->lat)
                                    {{ $info->city }} {{ $info->state ? states($info->state) : '' }}
                                    @else
                                    <a class="sbold" data-toggle="modal" href="#complete_address">Edit Address</a>
                                    @endif
                                </small>                                   
                            </div>
                            <div class="col-md-4 col-xs-6 mt-xs-10">
                                <i class="icon icon-badge pull-left""></i>
                                <small class="uppercase">
                                PROVIDER LICENSE<br>
                                <strong>{!! ($info->user_status) ? '<span class="text-primary">Verified</span>' : '<span class="text-danger">Not Verified</span>' !!}</strong>
                                </small>                         
                            </div>
                            <div class="col-md-3 col-xs-6 mt-xs-10">
                                <i class="icon icon-clock pull-left""></i>
                                <small class="uppercase">
                                {{ @number_format($appointments) }} Completed<br>
                                <strong class="uppercase">Appointments</strong>
                                </small>
                            </div>
                        </div>

        

                        <div class="row margin-top-20 bg-grey"> 
                            <div class="col-md-3">
                                <h3 class="no-margin"><i class="fa fa-dollar"></i> {{ $info->minimum_fee }}/hr 
                                <a class="small" data-toggle="modal" href="#minimum_fee"><i class="fa fa-pencil"></i></a></h3>
                            </div>


                            <div class="col-md-4 mt-xs-10">
                            <i class="icon icon-notebook pull-left"></i>
                            <small>
                                @if($confirmed)
                                    {{ number_format($confirmed) }} ACCEPTED <br> BOOKING REQUEST
                                @else
                                NO BOOKING REQUESTS<br> ACCEPTED YET
                                @endif
                            </small>                                
                            </div>


                            <div class="col-md-5 mt-xs-10">
                                <i class="icon icon-check pull-left"></i>
                                <small>
                                    AVAILABLE FOR <a class="" data-toggle="modal" href="#interested_in"><i class="fa fa-pencil"></i></a><br>
                                    <strong>{{ @work_status($info->temporary_assignments, $info->permanent_position) }}</strong>
                                </small>
                            </div>

                        </div>



                        <div class="portlet light bordered margin-top-20">
                            <div class="portlet-body">


                                 <h3 class="text-muted">Staffing Dental Provider #{{ sprintf('%07d', $info->id) }}</h3>

                                <p class="sbold">
                                    {{ $info->email }}
                                    <a class="btn" data-toggle="modal" href="#email_address"><i class="fa fa-pencil"></i></a>
                                </p>

                                <h4 class="sbold">Contact Information
                                    <a class="btn" data-toggle="modal" href="#complete_address"><i class="fa fa-pencil"></i></a>
                                </h4>

                                <p>
                                <i class="fa fa-phone"></i> <a href="tel:{{ $info->phone_number }}">{{ $info->phone_number }}</a><br>
                                <i class="fa fa-map-marker"></i> {{ $info->street_address }} {{ $info->city }} {{ $info->state }} {{ $info->zip_code }}
                                </p>

                                @if($info->resume || $info->government_issued_id)
                                    <h4 class="sbold">My Documents</h4>
                                    <ul>
                                    @if($info->resume)
                                    <li><a href="{{ asset($info->resume) }}" target="_blank" class="btn">View Resume</a> <a class="btn" data-toggle="modal" href="#resume"><i class="fa fa-pencil"></i></a></li>
                                    @endif

                                    @if($info->proof_of_insurance)
                                    <li><a data-href="{{ asset($info->proof_of_insurance) }}" class="btn-view btn" data-toggle="modal" data-target=".view-modal">View Proof of Malpractice Insurance</a>
                                        <a class="btn" data-toggle="modal" href="#proof_of_insurance">
                                        <i class="fa fa-pencil"></i></a>
                                    </li>
                                    @endif


                                    @if($info->government_issued_id)
                                    <li><a data-href="{{ asset($info->government_issued_id) }}" class="btn-view btn" data-toggle="modal" data-target=".view-modal">View Government Issued Pictured ID</a>
                                    <a class="btn" data-toggle="modal" href="#gevernment_issued_id"><i class="fa fa-pencil"></i></a></li>
                                    @endif
                                    </ul>
                                @endif

                                <h4 class="sbold">
                                    School Information
                                    <a class="btn" data-toggle="modal" href="#school_info"><i class="fa fa-pencil"></i></a>
                                </h4>
                                <p>{{ $info->dental_school_name }} @if($info->graduation_year) ({{ $info->graduation_year }}) @endif</p>

                                <h4 class="sbold">
                                    Dental License
                                    <a class="btn" data-toggle="modal" href="#dental_license"><i class="fa fa-pencil"></i></a>
                                </h4>
                                <p>{{ $info->dental_license }}</p>


                                <h4 class="sbold">
                                    Background Description 
                                    <a class="btn" data-toggle="modal" href="#background_description"><i class="fa fa-pencil"></i></a>
                                </h4>
                                <p class="text-justify">{{ @$info->background_description }}</p>


                                <div class="note note-info hide">
                                    <h5><b>Get a reward</b> share invitation below</h5>

                                    <div class="row">
                                        <div class="col-md-12 margin-top-10">
                                            <label class="col-md-3 control-label">
                                                Invitation Code
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                  <input type="text" id="mt-target-1" class="form-control" rows="3" readonly value="{{ @$info->my_invitation_code }}">
                                                  <a href="javascript:;" class="btn green mt-clipboard input-group-addon" data-clipboard-action="copy" data-clipboard-target="#mt-target-1">COPY</a>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 margin-top-10">
                                            <label class="col-md-3 control-label">
                                                Invitation link
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                  <input type="text" id="mt-target-2" class="form-control" rows="3" readonly value="{{ URL::route('auth.register') }}?invitation={{ @$info->my_invitation_code }}">
                                                  <a href="javascript:;" class="btn green mt-clipboard input-group-addon" data-clipboard-action="copy" data-clipboard-target="#mt-target-2">COPY</a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                

                            </div>

                        </div>    



                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@include('partials.review')


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
@endsection



@section('top_style')
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
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/pages/scripts/components-clipboard.min.js') }}" type="text/javascript"></script>  
<script src="{{ asset('assets/global/plugins/clipboardjs/clipboard.min.js') }}" type="text/javascript"></script>
@stop


@section('bottom_script')
<script>
$('[name="visibility"]').on('change.bootstrapSwitch', function(e) {
    var state = $(this).bootstrapSwitch('state');
    url = $(this).data('url');
    $('.profile-status').hide();
    if(state==true) {
        status = 'invisible';
        $('.p-invisible').show();    
    } else {
        $('.p-online').show();
        status = 'online';
    }
    data = { 
        key:'availability', 
        value: status,
        _token:$('[name="csrf-token"]').attr('content') 
    }
    $.post(url, data);
});
</script>
@stop


