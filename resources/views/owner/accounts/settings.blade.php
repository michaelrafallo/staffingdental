@extends('layouts.app')

@section('content')


<div class="row margin-top-40">
    <div class="col-md-3">

        <ul class="nav nav-pills nav-stacked">
            <li class="<?php echo (Input::get('tab') == 1) ? 'active' : ''; ?>">
                <a href="?tab=1">Account Settings </a>
            </li>
            <li class="<?php echo (Input::get('tab') == 2) ? 'active' : ''; ?>">
                <a href="?tab=2">Profile </a>
            </li>
            <li class="<?php echo (Input::get('tab') == 3) ? 'active' : ''; ?>">
                <a href="?tab=3">My Office</a>
            </li>
            <li class="<?php echo (Input::get('tab') == 4) ? 'active' : ''; ?>">
                <a href="?tab=4">Office Information</a>
            </li>
            <li class="<?php echo (Input::get('tab') == 5) ? 'active' : ''; ?>">
                <a href="?tab=5">Main Address </a>
            </li>
        </ul>

    </div>
    <div class="col-md-9">

        @include('notification')

        <form class="form-horizontal form-submit" method="post" action="{{ URL::route('owner.accounts.profile') }}" enctype="multipart/form-data">
            <div class="form-body">
                {!! csrf_field() !!}
                <input type="hidden" name="tab" value="{{ Input::get('tab') }}">

                <?php if(Input::get('tab') == 4): ?>

            <h2 class="no-margin">Office Information</h2>

            <div class="form-group margin-top-40">
                <label class="col-md-4 control-label">
                    Practice name
                </label>
                <div class="col-md-7">
                    <input type="text" name="practice_name" class="form-control" value="{{ Input::old('practice_name', @$info->practice_name) }}">
                    <!-- START error message -->
                    {!! $errors->first('practice_name','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Practice Description
                </label>
                <div class="col-md-7">
                    <textarea name="practice_description" class="form-control" rows="5">{{ Input::old('practice_description', @$info->practice_description) }}</textarea>
                    <!-- START error message -->
                    {!! $errors->first('practice_description','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>




            <div class="form-group">
                <label class="col-md-4 control-label">
                    Name of Dental Company
                </label>
                <div class="col-md-7">
                    <input type="text" name="company_name" class="form-control" value="{{ Input::old('company_name', @$info->company_name) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Doctor's Full Name
                </label>
                <div class="col-md-7">
                    <input type="text" name="doctor_fullname" class="form-control" value="{{ Input::old('doctor_fullname', @$info->doctor_fullname) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Office Manager's Full Name
                </label>
                <div class="col-md-7">
                    <input type="text" name="office_manager_full_name" class="form-control" value="{{ Input::old('office_manager_full_name', @$info->office_manager_full_name) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Office contact email
                </label>
                <div class="col-md-7">
                    <input type="email" name="office_contact_email" class="form-control" value="{{ Input::old('office_contact_email', @$info->office_contact_email) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Office contact phone
                </label>
                <div class="col-md-7">
                    <input type="text" name="office_contact_phone" class="form-control" value="{{ Input::old('office_contact_phone', @$info->office_contact_phone) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Payment terms
                </label>
                <div class="col-md-7">
                    {!! Form::select('payment_terms', payment_terms(), Input::old('payment_terms', @$info->payment_terms), ["class" => "form-control select2"]) !!}
                    <p class="help-block text-justify small">
                        Tell temporary staff how soon after service you will be able to pay them. Please note that most dental professionals prefer same-day payments. Selecting an option other than "same day" may limit your matching options and success rate.
                    </p>
                </div>
            </div>

            <div class="form-group">

                <label class="col-md-4 control-label">
                    Select all that apply
                </label>
                <div class="col-md-8">
                    <div class="row">

                        <?php 
                        $practice_type = Input::old('practice_type', json_decode($info->practice_type));

                        foreach(practice_types() as $practice_type_k => $practice_type_v): ?>
                        <?php 
                            $checked = (isset($practice_type)) ? (in_array($practice_type_k, $practice_type) ? 'checked' : '') : ''; 
                        ?>
                        <div class="col-md-6">
                            <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="practice_type[]" value="{{ $practice_type_k }}" 
                            {{ $checked }}> {{ $practice_type_v }}
                            <span></span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
                <?php elseif(Input::get('tab') == 5): ?>
                
                <h2 class="no-margin">Main Address</h2>


                <div class="form-group margin-top-40">
                    <label class="col-md-4 control-label">
                        Phone Number
                    </label>
                    <div class="col-md-7">
                        <input type="text" name="phone_number" class="form-control" value="{{ @$info->phone_number }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">
                        Address
                    </label>
                    <div class="col-md-7">
                    <input type="text" name="street_address" class="form-control" value="{{ @$info->street_address }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">
                       City
                    </label>
                    <div class="col-md-7">
                    <input type="text" name="city" class="form-control" value="{{ @$info->city }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">
                        State
                    </label>
                    <div class="col-md-7">
                    {!! Form::select('state', ['' => 'Select State'] + states(),  @$info->state, ["class" => "form-control select2"]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">
                        Zip code
                    </label>
                    <div class="col-md-7">
                    <input type="text" name="zip_code" class="form-control" value="{{ @$info->zip_code }}">
                    </div>
                </div>


                @endif


                <?php if(Input::get('tab') == 2): ?>
                
                <h2 class="no-margin">Profile</h2>

                <div class="well margin-top-20">
                    Please upload your profile picture and valid government issued picture ID                  
                </div>

              <div class="form-group">
                  <div class="col-md-3 text-center">
                  <label class="help-block">Profile Picture</label>

                      <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px;"> 
                          <img src="{{ has_photo($info->profile_picture) }}">
                          </div>
                          <div>
                              <span class="btn red btn-outline btn-file">
                                  <span class="fileinput-new"> Select image </span>
                                  <span class="fileinput-exists"> Change </span>
                                  <input type="file" name="profile_picture" accept="image/*"> </span>
                              <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-8 text-center">
                  <label class="help-block">Valid government issued picture ID</label>

                      <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px;"> 
                          <img src="{{ has_photo($info->government_issued_id) }}">
                          </div>
                          <div>
                              <span class="btn red btn-outline btn-file">
                                  <span class="fileinput-new"> Select image </span>
                                  <span class="fileinput-exists"> Change </span>
                                  <input type="file" name="government_issued_id" accept="image/*"> </span>
                              <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                          </div>
                      </div>
                  </div>

              </div>

                <div class="form-group hide">
                    <label class="col-md-4 control-label">
                        Invitation Code
                    </label>
                    <div class="col-md-7">
                        <div class="input-group">
                          <input type="text" id="mt-target-1" class="form-control" rows="3" readonly value="{{ @$info->my_invitation_code }}">
                          <a href="javascript:;" class="btn green mt-clipboard input-group-addon" data-clipboard-action="copy" data-clipboard-target="#mt-target-1">COPY</a>
                    </div>
                    </div>
                </div>

                <div class="form-group hide">
                    <label class="col-md-4 control-label">
                        Invitation link
                    </label>
                    <div class="col-md-7">
                        <div class="input-group">
                          <input type="text" id="mt-target-2" class="form-control" rows="3" readonly value="{{ URL::route('auth.register') }}?invitation={{ @$info->my_invitation_code }}">
                          <a href="javascript:;" class="btn green mt-clipboard input-group-addon" data-clipboard-action="copy" data-clipboard-target="#mt-target-2">COPY</a>
                    </div>
                    </div>
                </div>

            </div>

 
        <?php elseif(Input::get('tab') == 3): ?>

      <style>
      .portlet-office {
        height: 125px;
        border: 1px solid #e7ecf1;
        border-radius: 5px!important;
        box-shadow: 0 2px 3px 2px rgba(0,0,0,.03);
        overflow-y: auto;
      }         
      .portlet-office:hover {
        box-shadow: 2px 2px 5px 2px #337ab796;
      }     
      </style>

            <h2 class="no-margin">My Office</small>  
            <a href="{{ URL::route('owner.offices.add') }}" class="btn btn-sm uppercase"><i class="fa fa-plus"></i> Add New Office</a>
            </h2>

            <div class="row margin-top-20">

              <div class="col-md-6">
                <div class="portlet portlet-office light">

                @if( $info->lng )
                    <span class="online pull-right"></span>
                    <a href="{{ URL::route('owner.accounts.settings', ['tab' => 5]) }}" class="uppercase small">Edit</a> | 
                    <span class="text-muted small">DELETE</span>
                    <h5><a href="{{ URL::route('owner.accounts.settings', ['tab' => 5]) }}" class="sbold">Main Office</a></h5>
                    {{ ucwords(@$info->street_address.' '.@$info->city.' '.($info->state ? states($info->state) : '' ).' '.@$info->zip_code) }}
                @else
                    <div class="text-danger">
                    <h4 class="sbold">Your location could not be determined!</h4>
                    <div class="margin-bottom-10">Please make sure you give us your correct address.</div> 
                    <a href="{{ URL::route('owner.accounts.settings', ['tab' => 5]) }}" class="uppercase small sbold">Edit My Address</a>                        
                    </div>
                @endif

                  </div>
              </div>

              @foreach($offices as $office)
              <div class="col-md-6">
                <div class="portlet light portlet-office">
                  
                  <span class="{{ $office->post_status == 'actived' ? 'online' : 'offline' }} pull-right"></span>

                  <div class="small">
                    <a href="{{ URL::route('owner.offices.edit', $office->id) }}" class="uppercase">Edit</a> | 
                    @if($office->post_status != 'actived')
                    <a href="#" class="delete uppercase"
                        data-href="{{ URL::route('owner.offices.destroy', [$office->id, query_vars()]) }}" 
                        data-toggle="modal"
                        data-target=".delete-modal" 
                        data-title="Confirm Delete"
                        data-body="Are you sure you want to move to trash ID: <b>#{{ $office->id }}</b>?">Delete</a> 
                    @else
                    <span class="text-muted uppercase">Delete</span>
                    @endif                         
                  </div>

                  <h5><a href="{{ URL::route('owner.offices.edit', $office->id) }}" class="sbold">{{ ucwords($office->post_name) }}</a></h5>
                  <p class="no-margin">{{ $office->post_title }}</p>
                </div>    
              </div>
              @endforeach
            </div>


        <?php elseif(Input::get('tab') == 1): ?>
            
            <h2 class="no-margin">Account Settings</h2>

            <div class="form-group margin-top-40">
                <label class="col-md-4 control-label">
                </label>
                <div class="col-md-7">
                    <input type="hidden" name="email_notification" value="off">
                    <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="email_notification" value="on" {{ checked(@$info->email_notification, 'on') }}> Recieve email notifications
                    <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    First name
                </label>
                <div class="col-md-7">
                    <input type="text" name="firstname" class="form-control" value="{{ Input::old('firstname', @$info->firstname) }}">
                    <!-- START error message -->
                    {!! $errors->first('firstname','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Last name 
                </label>
                <div class="col-md-7">
                    <input type="text" name="lastname" class="form-control" value="{{ Input::old('lastname', @$info->lastname) }}">
                    <!-- START error message -->
                    {!! $errors->first('lastname','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Email
                </label>
                <div class="col-md-7">
                    <input type="text" name="email" class="form-control" value="{{ Input::old('email', @$info->email) }}">
                    <!-- START error message -->
                    {!! $errors->first('email','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    New password
                </label>
                <div class="col-md-7">
                    <input type="password" name="new_password" class="form-control" value="{{ Input::old('new_password') }}">
                    <span class="help-block small">Leave blank if you don't want to change it</span>
                    <!-- START error message -->
                    {!! $errors->first('new_password','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Confirm new password
                </label>
                <div class="col-md-7">
                    <input type="password" name="new_password_confirmation" class="form-control" value="{{ Input::old('new_password_confirmation') }}">
                    <span class="help-block small">Leave blank if you don't want to change it</span>
                    <!-- START error message -->
                    {!! $errors->first('new_password_confirmation','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

        <?php endif; ?>

        @if( Input::get('tab') != 3 )
        <div class="form-group margin-top-40">
            <div class="col-md-offset-4 col-md-8">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
        @endif

        </form>

    </div>  
</div>







@endsection



@section('top_style')

<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<style>
table.dataTable.no-footer {
    border-bottom-color: #e7ecf1;   
}   

</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
 
<script src="{{ asset('assets/pages/scripts/components-clipboard.min.js') }}" type="text/javascript"></script>  
<script src="{{ asset('assets/global/plugins/clipboardjs/clipboard.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>

$(".datepicker").datepicker();
$('.datatable').dataTable({
    "order": [[ 0, "desc" ]]
});
</script>
@include('partials.delete-modal')
@stop





