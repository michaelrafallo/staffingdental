<?php extract($user_data); ?>


<input type="hidden" name="form" value="4">
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
                <h4 class="sbold">Mailing Address</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Street Address <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="street_address" placeholder="Street Address" value="{{ Input::old('street_address', @$street_address) }}">
                            <!-- START error message -->
                            {!! $errors->first('street_address','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">City <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="city" placeholder="City" value="{{ Input::old('city', @$city) }}">
                        <!-- START error message -->
                        {!! $errors->first('city','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label">State <span class="required">*</span></label>
                    <div class="col-md-6">
                    {!! Form::select('state', ['' => 'Select State'] + states(), Input::old('state', @$state), ["class" => "form-control select2"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('state','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Zip Code <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="zip_code" placeholder="Zip Code" value="{{ Input::old('zip_code', @$zip_code) }}">
                        <!-- START error message -->
                        {!! $errors->first('zip_code','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>


                
            </td>
        </tr>
        <tr>
            <td>
                <h4 class="sbold">Contact</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Cell Phone Number <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="phone_number" placeholder="Cell Phone Number" value="{{ Input::old('phone_number', @$phone_number) }}">
                        <!-- START error message -->
                        {!! $errors->first('phone_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
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