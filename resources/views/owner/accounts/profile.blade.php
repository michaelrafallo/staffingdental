@extends('layouts.app')

@section('content')
<p><i class="{{ (in_array($info->status, ['approved', 'actived'])) ? 'online' : 'offline' }}"></i> <strong>Profile status:</strong> 
<span class="text-muted">

@if($info->status == 'pending')
Not yet live and pending for admin review

<div class="alert well">
    <h4 class="no-margin sbold">Your profile needs to be approved</h4>
    <p class="text-justify margin-top-20">Your profile needs to be approved to connect with our dental professionals. To ensure prompt approval of your profile, <strong>please make sure you have provided all required information</strong>, If you believe your profile is complete, and more than 24 hours have passed since you completed your profile, please contact customer support at <a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a>. We are here to support you in your career.</p>
</div>

@endif

@if( in_array($info->status, ['approved', 'actived']) ) 
live and connected with dental professionals
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
                        
                        <div class="thumbnail">
                            <a href="#profile_picture" class="btn btn-block" data-toggle="modal"><i class="fa fa-plus"></i> Upload Profile Picture</a>
                            <img src="{{ has_photo($info->profile_picture) }}" alt="">
                        </div>
   
                        <div class="text-center">
                            <a href="{{ URL::route('owner.accounts.settings', ['tab' => 1]) }}" class="btn btn-primary btn-sm btn-block uppercase" data-toggle="modal">Account Settings</a>

                            <div class="text-warning margin-top-10 h4">
                                {{ stars_review($overall_reviews) }}           
                            </div>
                            <small class="uppercase">{{ $all_reviews_count }} review{{ is_plural($all_reviews_count) }}</small>

                        </div>


                        <?php 
                        $profile_count = 7;
                        $complete = 0; 
                        ?>

                        <ul class="list-unstyled profile-nav margin-top-10">
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

                            @if( ! $info->practice_name)
                            <li>
                                <a href="#practice_name" class="btn-block" data-toggle="modal">
                                <i class="fa fa-plus"></i> Add Practice Name</a>                                
                            </li>
                            <?php $complete += 1; ?>
                            @endif

                            @if( ! $info->company_name)
                            <li>
                                <a href="#company_name" class="btn-block" data-toggle="modal">
                                <i class="fa fa-plus"></i> Add Company Name</a>                                
                            </li>
                            <?php $complete += 1; ?>
                            @endif

                            @if( ! $info->practice_description)
                            <li>
                                <a href="#practice_description" class="btn-block" data-toggle="modal">
                                <i class="fa fa-plus"></i> Add Practice Description</a>
                            </li>
                            <?php $complete += 1; ?>
                            @endif

                            @if( ! $info->profile_picture)
                            <li>
                                <a href="#profile_picture" class="btn-block" data-toggle="modal">
                                <i class="fa fa-plus"></i> Upload profile picture</a>
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

                            @if( ! $info->email_verified)
                            <li>
                                <a href="#email_address" class="btn-block" data-toggle="modal">
                                <i class="fa fa-plus"></i> Confirm Your Email Address</a>
                            </li>
                            <?php $complete += 1; ?>
                            @endif
                        </ul>

                        <?php $completeness = number_format( (($profile_count-$complete)/$profile_count) * 100 ); ?>
                        <div class="progress progress-striped">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: {{ $completeness }}%">
                                <span class="sr-only"> {{ $completeness }}% Complete (success) </span>
                            </div>
                        </div>
                        
                        Profile Completeness: <b class="text-danger">{{ $completeness }}%</b>

                        @include('partials/social-login')

                    </div>


                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 profile-info">
                                <h1 class="font-green sbold uppercase">{{ $info->fullname }}</h1>
                                <h4>
                                @if($info->company_name)
                                {{ $info->company_name }}
                                @else
                                Dental Owner
                                @endif
                                 <a class="btn" data-toggle="modal" href="#company_name"><i class="fa fa-pencil"></i></a></h4>
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


                            <div class="col-md-3">
                                <i class="icon icon-clock pull-left""></i>
                                <small class="uppercase">
                                {{ number_format($appointments) }} Completed<br>
                                <strong class="uppercase">Appointments</strong>
                                </small>
                            </div>

                            <div class="col-md-3">
                                <i class="icon icon-users pull-left""></i>
                                <small class="uppercase">
                                {{ number_format($hired) }} Hired<br>
                                <strong class="uppercase">Professionals</strong>
                                </small>
                            </div>

                        </div>



                        <div class="portlet light bordered margin-top-20">
                            <div class="portlet-body">

                                <h4 class="sbold">Email Address
                                    <a class="btn" data-toggle="modal" href="#email_address"><i class="fa fa-pencil"></i></a>
                                </h4>
                                <p><a href="mailto:{{ $info->email }}">{{ $info->email }}</a></p>

                                <h4 class="sbold">Contact Information
                                    <a class="btn" data-toggle="modal" href="#complete_address"><i class="fa fa-pencil"></i></a>
                                </h4>
                                <p>

                                @if( $info->phone_number )
                                <i class="fa fa-phone"></i> <a href="tel:{{ $info->phone_number }}">{{ $info->phone_number }}</a><br>
                                @endif

                                @if( $info->lat )
                                <i class="fa fa-map-marker"></i> {{ $info->street_address }} {{ $info->city }} {{ $info->state }} {{ $info->zip_code }}
                                @endif
                                
                                </p>

                                <h4 class="sbold">Practice Name
                                    <a class="btn" data-toggle="modal" href="#practice_name"><i class="fa fa-pencil"></i></a>
                                </h4>
                                <p>{{ $info->practice_name }}
                                </p>

                                <h4 class="sbold">
                                    Practice Description 
                                    <a class="btn" data-toggle="modal" href="#practice_description"><i class="fa fa-pencil"></i></a>
                                </h4>


                                <p class="text-justify">{{ $info->practice_description }}</p>

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



@endsection



@section('top_style')
<link href="{{ asset('assets/pages/css/profile-2.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.icon {
    color: rgba(0,0,0,0.4);
    margin: 13px 10px 0;
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
</style>

@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/pages/scripts/components-clipboard.min.js') }}" type="text/javascript"></script>  
<script src="{{ asset('assets/global/plugins/clipboardjs/clipboard.min.js') }}" type="text/javascript"></script>
@stop


@section('bottom_script')

@stop
