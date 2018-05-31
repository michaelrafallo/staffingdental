@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="page-title"> General Settings</h1>
    </div>
</div>

@include('notification')

<div class="row">

    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body form">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="op" value="1">   
                    <div class="tab-content">
                        <!-- START TAB 1 -->
                        <div class="tab-pane active in" id="tab_1">

                            <h3>System</h3>
                            <hr>

                            <div class="form-group margin-top-30">
                                <label class="col-md-3 control-label">Admin Email</label>
                                <div class="col-md-8">
                                    <div class="input-icon">
                                        <i class="fa fa-envelope"></i>
                                        <input type="email" class="form-control" name="admin_email" placeholder="Admin Email" value="{{ @$info->admin_email }}"> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Email Carbon Copy</label>
                                <div class="col-md-8">
                                    <div class="input-icon">
                                        <i class="fa fa-envelope"></i>
                                        <input type="text" class="form-control" name="carbon_copy" placeholder="Email Carbon Copy" value="{{ @$info->carbon_copy }}">
                                        <div class="help-inline">Multiple email address must seperated by comma</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Site Title</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="site_title" placeholder="Site Title" value="{{ @$info->site_title }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Contact Number</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="contact_no" placeholder="Contact Number" value="{{ @$info->contact_no }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Copy Right</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="copy_right" placeholder="Copy Right" value="{{ @$info->copy_right }}"> 
                                </div>
                            </div>

                            <div class="form-group margin-top-30">
                                <label class="col-md-3 control-label">Logo</label>
                                <div class="col-md-4">
                                    <img src="{{ asset(@$info->logo) }}" class="img-thumbnail">
                                    <input type="file" class="form-control margin-top-10" name="logo"> 
                                </div>
                            </div>
                            
                            <hr>
                            <h3>Default Messages</h3>
                            <hr>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Invite Applicant</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="applicant_invite" rows="3">{{ @$info->applicant_invite }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Hire Applicant</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="applicant_hire" rows="3">{{ @$info->applicant_hire }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Cancel Application</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="applicant_cancel" rows="3">{{ @$info->applicant_cancel }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Application Cover Letter</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="applicant_cover_letter" rows="3">{{ @$info->applicant_cover_letter }}</textarea>
                                    <span class="help-inline">Cover letter for application sending by applicant</span>
                                </div>
                            </div>

                            <div class="hide"> 

                                <hr>
                                <h3>Reward Points</h3>
                                <hr>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dental Professional</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="6" class="form-control numeric" name="dental_professional_reward" placeholder="Dental Professional Referral Points" value="{{ @$info->dental_professional_reward }}" min="0" step="any"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Dental Office</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="6" class="form-control numeric" name="dental_office_reward" placeholder="Dental Office Referral Points" value="{{ @$info->dental_office_reward }}" min="0" step="any"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Engagement Reward</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="6" class="form-control numeric" name="engagement_reward" placeholder="Engagement Reward Points" value="{{ @$info->engagement_reward }}" min="0" step="any"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Appointment Reward</label>
                                    <div class="col-md-2">
                                        <input type="text" maxlength="6" class="form-control numeric" name="appointment_reward" placeholder="Appointment Reward Points" value="{{ @$info->appointment_reward }}" min="0" step="any"> 
                                    </div>
                                </div>

                            </div>

                            <hr>
                            <h3>Account</h3>
                            <hr>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Free Trial Period</label>
                                <div class="col-md-2">
                                    <input type="text" maxlength="6" class="form-control numeric" name="free_trial_period" placeholder="Free Trial Period" value="{{ @$info->free_trial_period }}" min="1" step="any"> 
                                </div>
                                <span class="help-inline">Days</span>
                            </div>

                          <div class="form-group margin-top-30">
                              <label class="col-md-3 control-label"></label>
                              <div class="col-md-8">
                              <div class="mt-checkbox-list">
                                  <label class="mt-checkbox mt-checkbox-outline">   
                                      <input type="hidden" name="enable_free_trial" value="false">
                                      <input type="checkbox" name="enable_free_trial" value="1" {{ checked(Input::old('enable_free_trial', $info->enable_free_trial), '1') }}> Enable Free Trial 

                                      <span></span>
                                  </label>
                              </div>              
                              </div>

                              <label class="col-md-3 control-label"></label>
                              <div class="col-md-8">
                              <div class="mt-checkbox-list">
                                  <label class="mt-checkbox mt-checkbox-outline">   
                                      <input type="hidden" name="auto_approve_account" value="false">
                                      <input type="checkbox" name="auto_approve_account" value="1" {{ checked(Input::old('auto_approve_account', $info->auto_approve_account), '1') }}> Auto-approved New Account 

                                      <span></span>
                                  </label>
                              </div>              
                              </div>

                              <label class="col-md-3 control-label"></label>
                              <div class="col-md-8">
                              <div class="mt-checkbox-list">
                                  <label class="mt-checkbox mt-checkbox-outline">   
                                      <input type="hidden" name="socialite_connect" value="false">
                                      <input type="checkbox" name="socialite_connect" value="1" {{ checked(Input::old('socialite_connect', $info->socialite_connect), '1') }}> Enable Social Account Login 

                                      <span></span>
                                  </label>
                              </div>              
                              </div>
                          </div>



                        </div>
                        <!-- END TAB 1 -->
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection



@section('top_style')
<style>
    .select2-selection {
    min-height: 34px;
    max-height: 34px;
    padding: 3px;
    border: 1px solid #c2cad8!important;
    }
    .select2-selection__arrow {
    padding: 15px;
    }   
</style>
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

<script>
$(".select2").select2({width: '100%'});
</script>
@stop
