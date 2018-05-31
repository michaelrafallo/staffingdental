<?php extract($user_data); ?>


<input type="hidden" name="form" value="video">
<input type="hidden" name="user_type_provider" value="provider">

<!-- START STEP 3-->
<div class="col-md-10 col-centered provider-form">
    <div class="alert alert-info">
        <h4>Questions or concerns?</h4>
        <p>
        Email us at <strong><a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a></strong>.
        </p>
    </div>
    <table class="table table-hover table-striped">
        <tr>
            <td>
                <h4 class="sbold">Account</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Password <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::old('password', @$password) }}">
                        <span class="help-inline">Password must contain 1 upper case letter, 1 number and minimum of 6 characters.</span>
                        <!-- START error message -->
                        {!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Repeat Password <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password" value="{{ Input::old('password_confirmation', @$password_confirmation) }}">
                        <!-- START error message -->
                        {!! $errors->first('password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>
            </td>
        </tr>
        <tr> 
            <td>
            
            <label class="mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="agree" value="1" {{ checked(1, Input::old('agree')) }}>
            I have read, understand and agree to the Staffing Dental <a href="#terms-of-use" data-toggle="modal">Terms of Use</a> and <a href="#privacy-policy" data-toggle="modal">Privacy Policy.</a> 
            <span></span>
            </label>
            <!-- START error message -->
            {!! $errors->first('agree','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
            <!-- END error message -->
            </td>
        </tr>
    </table>

    <div class="form-group">
        <div class="col-md-12">
        <a href="{{ URL::route('auth.register', 2) }}" class="btn green btn-lg btn-step"><i class="fa fa-angle-double-left"></i> Previous</a>

        <button type="submit" class="btn green btn-lg btn-step pull-right">Next <i class="fa fa-angle-double-right"></i></button>
        </div>
    </div>
</div>
<!-- END STEP 3-->