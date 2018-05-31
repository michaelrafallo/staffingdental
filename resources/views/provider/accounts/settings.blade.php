@extends('layouts.app')

@section('content')


<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Account Settings</h1>
<!-- END PAGE TITLE-->

<div class="row">
    <div class="col-md-3">

        <ul class="nav nav-pills nav-stacked">
            <li class="{{ (Input::get('tab') == 1) ? 'active' : '' }}">
                <a href="?tab=1">Account Settings </a>
            </li>
            <li class="{{ (Input::get('tab') == 2) ? 'active' : '' }}">
                <a href="?tab=2">Services Offered </a>
            </li>
            <li class="{{ (Input::get('tab') == 3) ? 'active' : '' }}">
                <a href="?tab=3">Professional Background </a>
            </li>
            <li class="{{ (Input::get('tab') == 4) ? 'active' : '' }}">
                <a href="?tab=4">Profile </a>
            </li>
            <li class="{{ (Input::get('tab') == 5) ? 'active' : '' }}">
                <a href="?tab=5">Contact Information </a>
            </li>
        </ul>

    </div>
    <div class="col-md-9">

        @include('notification')

        <form class="form-horizontal form-submit" method="post" action="{{ URL::route('provider.accounts.profile') }}" enctype="multipart/form-data">
            <div class="form-body">
                {!! csrf_field() !!}

                <input type="hidden" name="tab" value="{{ Input::get('tab', 1) }}">

                <?php if(Input::get('tab') == 5): ?>
                
                <h2 class="no-margin">Contact Information</h2>


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

                @if(Input::get('tab') == 4)
                
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

              <hr>

                <div class="form-group">
                    <div class="col-md-7">
                    <label>
                        Phone
                    </label>

                        <input type="text" name="phone_number" class="form-control" value="{{ Input::old('phone_number', @$info->phone_number) }}">
                        <!-- START error message -->
                        {!! $errors->first('phone_number','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
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



                <div class="form-group hide">
                    <div class="col-md-7 col-md-offset-4">
                        <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="public_profile" value="1" {{ checked(1, Input::old('public_profile', @$info->public_profile)) }}> 
                        Make profile public
                        <span></span>
                        </label>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-7">
        
                    <label>Languages Spoken English</label>

                <div class="mt-repeater">
                <div class="form-group no-margin">
                    <div data-repeater-list="languages">

                        @if( is_array( @json_decode($info->languages) ) ) 
                        @foreach(json_decode($info->languages) as $language)
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-6 col-xs-5">

                                {!! Form::select('user_languages', [''  => 'Select Language'] + user_languages(), Input::old('user_languages', @$language->user_languages), ["class" => "form-control select2"]) !!}

                                </div>
                                <div class="col-md-5 col-xs-5">

                                {!! Form::select('fluency', [''  => 'Select Fluency'] + fluency(), Input::old('fluency', @$language->fluency), ["class" => "form-control select2"]) !!}

                                </div>
                                <div class="col-md-1 col-xs-1">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-6 col-xs-5">

                                {!! Form::select('user_languages', [''  => 'Select Language'] + user_languages(), Input::old('user_languages', @$language->user_languages), ["class" => "form-control select2"]) !!}

                                </div>
                                <div class="col-md-5 col-xs-5">

                                {!! Form::select('fluency', [''  => 'Select Fluency'] + fluency(), '', ["class" => "form-control select2"]) !!}

                                </div>
                                <div class="col-md-1 col-xs-1">
                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    </div>
                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i> Add Language</a>
                    </div>

                    </div>
                </div>




            @elseif(Input::get('tab') == 2)

            <h2 class="no-margin">Services Offered</h2>

            <div class="form-group margin-top-20">
                <label class="col-md-4 control-label">
                    Minimum Hours
                </label>
                <div class="col-md-7">
                    <input type="text" maxlength="6" class="form-control numeric" name="minimum_hours" value="{{ Input::old('minimum_hours', @$info->minimum_hours) }}">
                    <p class="help-block">What is the minimum number of hours you would like to request, from offices that send you work assignments?</p>
                    <!-- START error message -->
                    {!! $errors->first('minimum_hours','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Minimum Fee
                </label>
                <div class="col-md-7">
                    <input type="text" maxlength="6" class="form-control numeric" name="minimum_fee" value="{{ Input::old('minimum_fee', @$info->minimum_fee) }}">
                    <p class="help-block">How much do you charge on an hourly basis?</p>
                    <!-- START error message -->
                    {!! $errors->first('minimum_fee','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Maximum Distance
                </label>
                <div class="col-md-7">
                    <input type="text" maxlength="6" class="form-control numeric" name="travel_distance" value="{{ Input::old('travel_distance', @$info->travel_distance) }}">
                    <p class="help-block">The maximum distance, in miles, you are willing to travel outside of your zip code to perform dental procedures</p>
                    <!-- START error message -->
                    {!! $errors->first('travel_distance','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>


            @elseif(Input::get('tab') == 3)

            <h2 class="no-margin">Professional Background</h2>

            <div class="form-group margin-top-20">
                <label class="col-md-4 control-label">Are you a student?</label>
                <div class="col-md-7">
                    <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="student" value="yes" {{ checked(Input::old('student', @$info->student), 'yes') }}> Yes, {{ provider_info($info->provider_type)->student }}
                    <span></span>
                    </label>
                </div>
            </div>

              <div class="form-group">
                    <label class="col-md-4 control-label">{{ provider_info($info->provider_type)->school_name }}</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="dental_school_name" value="{{ Input::old('dental_school_name', @$info->dental_school_name) }}">
                        <!-- START error message -->
                        {!! $errors->first('dental_school_name','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Graduation Year</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="graduation_year" value="{{ Input::old('graduation_year', @$info->graduation_year) }}" maxlength="4">
                        <!-- START error message -->
                        {!! $errors->first('graduation_year','<span class="help-block text-danger">:message</span>') !!}
                        <!-- END error message -->
                    </div>
                </div>


            <div class="form-group">
                    <label class="col-md-4 control-label">Description of your background</label>
                    <div class="col-md-7">
                    <textarea name="background_description" class="form-control" rows="5">{{ Input::old('background_description', @$info->background_description) }}</textarea>
                    <p class="help-block">Describe you previous experiences, work history, education and accolades</p>
                    <!-- START error message -->
                    {!! $errors->first('background_description','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-4 control-label">Speciality Type</label>
                    <div class="col-md-7">

                      {!! Form::select('special_type', ['' => 'Select special type'] + special_type(), Input::old('special_type', @$info->special_type), ["class" => "form-control select2"]) !!}
                    </div>
                </div>

                @if(has_dental_license(@$info->provider_type))
                <div class="form-group">
                    <label class="col-md-4 control-label">Dental License Number</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="dental_license" value="{{ Input::old('dental_license', @$info->dental_license) }}">
                    </div>
                </div>
                 @endif

                <div class="form-group">
                    <label class="col-md-4 control-label">Resume</label>
                    <div class="col-md-7">
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                        <span class="help-inline">Accepted file types : .pdf, .doc, docx</span> 

                        @if(@$info->resume)                  
                        <a href="{{ asset($info->resume) }}" class="btn" target="_blank">View Resume</a>
                        @endif

                        <span class="help-block">Upload your resume to help potential employers see you, and see your qualifications.</span>                  
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Proof of Malpractice Insurance</label>
                    <div class="col-md-7">
                        <input type="file" class="form-control" name="proof_of_insurance">
                        <span class="help-inline">Allowed files: JPEG, JPG and PNG</span>
                        
                        @if(@$info->proof_of_insurance)
                        <div class="margin-top-10">                           
                        <a href="{{ asset($info->proof_of_insurance) }}" download>Download Insurance File</a> | 
                        <a data-href="{{ asset($info->proof_of_insurance) }}" class="btn-view" data-toggle="modal" data-target=".view-modal">View Image</a>
                        </div>
                        @endif
                        <span class="help-block">Upload your professional malpractice insurance declaration page, and be eligible to work for more offices.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Expiration Date</label>
                    <div class="col-md-7">
                        <input type="text" class="form-control datepicker" name="expiry_date" data-date-format="dd-M-yyyy" readonly="" value="{{ Input::old('expiry_date', @$info->expiry_date) }}">
                        <p class="help-block">Please enter the date on which your insurance policy expires. By entering a date, you certify that it is true and correct. Dental offices rely on the accuracy of this information.</p>
                    </div>
                </div>


                <div class="form-group hide">
                    <label class="col-md-4 control-label">Skills</label>
                    <div class="col-md-7">
                    {!! Form::select('skills[]', skills(), Input::old('skills', json_decode(@$info->skills)), ["class" => "form-control select2", "multiple"]) !!}
                
                    </div>
                </div>                
                
                <div class="col-md-12">
                    <label class="col-md-4 control-label">I am interested in</label>
                    <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="permanent_position" value="1" {{ checked(1, @$info->permanent_position) }}> A permanent position in a dental office
                    <span></span>
                    </label>

                    <label class="mt-checkbox mt-checkbox-outline col-md-offset-4">
                    <input type="checkbox" name="temporary_assignments" value="1" {{ checked(1, @$info->temporary_assignments) }}> Temporary assignments in dental offices
                    <span></span>
                    </label>
                </div>

            @elseif(Input::get('tab') == 1)
            
            <h2 class="no-margin">Account Settings</h2>

            <div class="form-group margin-top-40">
                <label class="col-md-4 control-label">
                </label>
                <input type="hidden" name="email_notification" value="off">
                <div class="col-md-7">
                    <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="email_notification" value="on" {{ checked(@$info->email_notification, 'on') }}> Recieve email notifications
                    <span></span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-4 control-label">
                    First Name
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
                    Last Name 
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
                    Email Address
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
                    New Password
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
                    Confirm New Password
                </label>
                <div class="col-md-7">
                    <input type="password" name="new_password_confirmation" class="form-control" value="{{ Input::old('new_password_confirmation') }}">
                    <span class="help-block small">Leave blank if you don't want to change it</span>
                    <!-- START error message -->
                    {!! $errors->first('new_password_confirmation','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg margin-top-40">Save Changes</button>
        </div>
        <div class="margin-top-40"></div>
        </form>



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

@endsection



@section('top_style')


<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

    
<style>

.mt-repeater .mt-repeater-delete{
    margin: 0;
}
.no-margin {
    margin: 0!important;
}
</style>
@stop

@section('bottom_style')
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('bottom_plugin_script') 

<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>    
<script src="{{ asset('assets/pages/scripts/components-clipboard.min.js') }}" type="text/javascript"></script>  
<script src="{{ asset('assets/global/plugins/clipboardjs/clipboard.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')


@stop
