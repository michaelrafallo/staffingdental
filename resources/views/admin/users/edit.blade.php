@extends('layouts.app')

@section('breadcrumb')
<ul class="page-breadcrumb uppercase">
    <li>
        <a href="{{ URL::route('admin.users.index') }}">Users</a>
        <i class="fa fa-circle"></i>
    </li>
    <li class="sbold">
        <span>Edit User</span>
    </li>
</ul>
@endsection

@section('content')

<div class="row margin-top-30">
    <div class="col-md-12">
        @include('notification')
    </div>

    <div class="col-md-8">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-user font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">{{ $info->firstname.' '.$info->lastname }}</span>
                    <small class="uppercase text-muted"> {{ user_group($info->group) }} </small>
                </div>

                <div class="pull-right">
                  <a href="{{ URL::route('backend.users.login', $info->id) }}" class="btn btn-default btn-sm uppercase margin-top-10"><i class="fa fa-sign-in"></i> Login</a>

                </div>

            </div>
            <div class="portlet-body">

            <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

            {!! csrf_field() !!}
            <input type="hidden" name="op" value="1">   

              @if(@$info->package_expiry)
              {!! (@$info->package_amount == 0) ? '<span class="text-danger">Free Trial Account</span>' : '<span class="text-primary">Premium Account</span>' !!}<br>
              Valid until {{ date('d-M-Y', strtotime($info->package_expiry)) }}

              <hr>
              @endif

              <div class="form-group">
                  <label class="col-md-4 control-label">Picture</label>
                  <div class="col-md-4">
                    <label class="help-block">
                      <a data-href="{{ asset($pic) }}" class="btn-view" data-toggle="modal" data-target=".view-modal">View</a> 
                      Profile Picture
                    </label>
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"> 
                          <img src="{{ has_photo($pic) }}">
                          </div>
                          <div>
                              <span class="btn red btn-outline btn-file btn-block btn-sm uppercase">
                                  <span class="fileinput-new"> Select image </span>
                                  <span class="fileinput-exists"> Change </span>
                                  <input type="file" name="file"> </span>
                          </div>
                      </div>
                  </div>

                  @if( $info->group == 'provider' )
                  <div class="col-md-4">
                      <label class="help-block">
                      <a data-href="{{ asset($info->government_issued_id) }}" class="btn-view" data-toggle="modal" data-target=".view-modal">View</a>
                      Government ID
                      </label>

                      <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="height: 150px;"> 
                          <img src="{{ has_photo($info->government_issued_id) }}">
                          </div>
                      </div>
                  </div>
                  @endif
                  
              </div>
                
              <div class="form-group">
                  <label class="col-md-4 control-label">First Name</label>
                  <div class="col-md-8">
                      <input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ $info->firstname }}">
                        <!-- START error message -->
                        {!! $errors->first('firstname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-md-4 control-label">Last Name</label>
                  <div class="col-md-8">
                      <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ $info->lastname }}">
                        <!-- START error message -->
                        {!! $errors->first('lastname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                  </div>
              </div>


            <div class="form-group">
                <label class="col-md-4 control-label">Email</label>
                <div class="col-md-8">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $info->email }}"> </div>
                        <!-- START error message -->
                        {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                </div>
            </div>

              <div class="form-group">
                  <label class="col-md-4 control-label">Status</label>
                  <div class="col-md-8">
                    {!! Form::select('status',  user_status(), Input::old('status', $info->status), ["class" => "form-control"]) !!}
                  </div>
              </div>

              @if( in_array($info->group, ['provider', 'owner']) )

              <div class="form-group">
                  <label class="col-md-4 control-label"></label>
                  <div class="col-md-8">
                  <div class="mt-checkbox-list">
                      <label class="mt-checkbox mt-checkbox-outline">
                          <input type="checkbox" name="user_status" value="verified" {{ checked(Input::old('user_status', $info->user_status), 'verified') }}> Verified User 
                          @if($info->group == 'provider')
                          | <a data-href="{{ asset($info->proof_of_insurance) }}" class="btn-view" data-toggle="modal" data-target=".view-modal">View Image</a>
                          @endif
                          <span></span>
                      </label>
                  </div>              
                  </div>
              </div>
              @endif

                <div class="form-group">
                    <div class="col-md-offset-4 col-md-7">
                        <button type="submit" class="btn blue">Update Changes</button> 
                    </div>
                </div>


            </form>

            </div>
        </div>
    </div>    


    <div class="col-md-4">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-key font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Change Password</span>
                </div>
            </div>
            <div class="portlet-body">
     
            <form class="form-horizontal" role="form" method="post">
             
                {!! csrf_field() !!}
                <input type="hidden" name="op" value="2">   

                <div class="form-group">
                    <div class="col-md-12">
                      <label class="control-label">New Password</label>
                        <div class="input-icon right">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-control" name="new_password" placeholder="New Password" value="{{ Input::old('new_password') }}"> </div>
                        <!-- START error message -->
                        {!! $errors->first('new_password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                      <label class="control-label">Confirm New Password</label>
                        <div class="input-icon right">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-control" name="new_password_confirmation" placeholder="Confirm New Password" value="{{ Input::old('new_password_confirmation') }}"> </div>
                        <!-- START error message -->
                        {!! $errors->first('new_password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

                

                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn blue">Update Password</button>
                    </div>
                </div>
            </form>

            </div>
        </div>


    </div>    

    @if( count($details) > 1 )
    <div class="col-md-8">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-user font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">User Details</span>
                </div>
            </div>
            <div class="portlet-body">

            <div class="table-scrollable">
              <table class="table table-striped">
                @foreach($details as $detail)
                <tr>
                  <td width="30%" class="text-right"><h5 class="no-margin">{{ code_to_text($detail->meta_key) }} :</h5></td>
                  <td>{!! array_val_formatted($detail->meta_key, $detail->meta_value) !!}</td>
                </tr>
                @endforeach
              </table>
              </div>

            </div>
        </div>
    </div>    
    @endif

</div>

@if( in_array($info->group, ['provider', 'owner']) ) 
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

<iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode($address) }}&amp;output=embed"></iframe>
@endif


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

@stop



@section('top_style')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
@stop

@section('bottom_script')

@include('partials.delete-modal')

</script>
@stop




