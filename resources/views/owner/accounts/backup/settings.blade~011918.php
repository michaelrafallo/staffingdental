@extends('layouts.app')

@section('content')


<div class="row margin-top-40">
    <div class="col-md-3">

        <ul class="nav nav-pills nav-stacked">
            <li class="<?php echo (Input::get('tab') == 1) ? 'active' : ''; ?>">
                <a href="?tab=1">Account Settings </a>
            </li>
            <li class="<?php echo (Input::get('tab') == 2) ? 'active' : ''; ?>">
                <a href="?tab=2">Office Information </a>
            </li>
            <li class="<?php echo (Input::get('tab') == 3) ? 'active' : ''; ?>">
                <a href="?tab=3">Profile </a>
            </li>
            <li class="<?php echo (Input::get('tab') == 4) ? 'active' : ''; ?>">
                <a href="?tab=4">Contact Information </a>
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


                <?php if(Input::get('tab') == 3): ?>
                
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
                    <label class="col-md-4 control-label">
                        Phone
                    </label>
                    <div class="col-md-7">
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

            </div>

        <?php elseif(Input::get('tab') == 2): ?>

            <h2 class="no-margin">Office Requests  
            <a href="{{ URL::route('owner.offices.add') }}" class="btn btn-sm uppercase"><i class="fa fa-plus"></i> Add New Office</a>
            </h2>
            
            <div class="margin-top-40">
            <table class="table margin-top-20 datatable">  
               <thead>
                  <tr>
                     <th>Offices</th>
                     <th>Date Requested</th>
                     <th>Status</th>
                     <th></th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($requests as $request)
                  <tr>
                     <td><b>{{ $office_count = count( json_decode($request->post_content) ) }}</b> Dental Office{{ is_plural($office_count) }}</td>
                     <td>
                      {{ date_formatted($request->created_at) }}
                      <p class="no-margin text-muted">{{ time_ago($request->created_at) }}</p>
                      </td>
                     <td>
                      {{ status_ico($request->post_status) }}
                      
                      @if( $request->post_name )
                      <h5 class="sbold">{{ amount_formatted($request->post_name) }} / month</h5>
                      @endif

                     </td>
                     <td><a href="{{ URL::route('owner.offices.view', $request->id) }}">View</a></td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
            </div>

            <h2 class="">My Dental Offices</h2>
            
            <div class="margin-top-20">
            <table class="table datatable">  
               <thead>
                  <tr>
                     <th>Dental Company</th>
                     <th>Address</th>
                     <th></th>
                  </tr>
               </thead>
               <tbody>
                <tr>
                  <td>Main Office</td>
                  <td>{{ ucwords($info->street_address.' '.$info->city.' '.states($info->state).' '.$info->zip_code) }}</td>
                </tr>
                  @foreach($offices as $office)
                  <tr>
                     <td>{{ $office->post_name }}</td>
                     <td>{{ ucwords($office->post_title) }}</td>
                     <td><a href="{{ URL::route('owner.offices.edit', $office->id) }}">Edit</a></td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
            </div>


        <?php elseif(Input::get('tab') == 1): ?>
            
            <h2 class="no-margin">Account Settings</h2>

            <div class="form-group margin-top-40">
                <label class="col-md-4 control-label">
                </label>
                <div class="col-md-7">
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

        @if( Input::get('tab') != 2 )
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
@stop





