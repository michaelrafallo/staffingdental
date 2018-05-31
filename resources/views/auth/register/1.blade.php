<?php extract($user_data); ?>

<input type="hidden" name="form" value="2">

<!-- START STEP 1-->
<div class="col-md-9 col-centered step-1">
    <div class="form-group">
        <label class="col-md-3 control-label">Name <span class="required">*</span></label>

        <div class="col-md-3">
            <input type="text" class="form-control" name="firstname" placeholder="First name" value="{{ Input::old('firstname', @$firstname) }}">
          <!-- START error message -->
          {!! $errors->first('firstname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
          <!-- END error message -->
        </div>
        <div class="col-md-3 mt-xs-10">
            <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ Input::old('lastname', @$lastname) }}">
          <!-- START error message -->
          {!! $errors->first('lastname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
          <!-- END error message -->
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Email <span class="required">*</span></label>
        <div class="col-md-6">
            <input type="text" class="form-control" name="email" placeholder="Email" value="{{ Input::old('email', @$email) }}">
            <!-- START error message -->
            {!! $errors->first('email','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
            <!-- END error message -->
        </div>
    </div>

    <div class="form-group text-center hide">
        <div class="col-md-6 col-centered">
            <a class="btn btn-toggle" data-target="invitation-code">Add Invitation Code</a> 
        </div>
    </div>

    <?php $invitation = Input::get('invitation'); ?>
    <div  class="invitation-code" style="{{ ((@$invitation_code) ? $invitation_code : $invitation) ? '' : 'display:none;' }}">
        <div class="form-group">
            <label class="col-md-3 control-label">Invitation Code</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="invitation_code" placeholder="Invitation Code" 
                value="{{ (@$invitation_code) ? $invitation_code : $invitation }}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-3 col-md-6">
            <button type="submit" class="btn green btn-lg btn-step pull-right">Next <i class="fa fa-angle-double-right"></i></button>
        </div>
    </div>
</div>
<!-- END STEP 1 -->
