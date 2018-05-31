<?php extract($user_data); ?>

<input type="hidden" name="form" value="video">
<input type="hidden" name="user_type_provider" value="owner">

<!-- START STEP 3-->
<div class="col-md-10 col-centered step-3 owner-form">
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
                        <input type="password" class="form-control" name="password" placeholder="Password" value="{{ Input::get('password', @$password) }}">
                        <span class="help-inline">Password must contain 1 upper case letter, 1 number and minimum of 6 characters.</span>
<!-- START error message -->
{!! $errors->first('password','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Repeat Password <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password" value="{{ Input::get('password_confirmation', @$password_confirmation) }}">
<!-- START error message -->
{!! $errors->first('password_confirmation','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <h4 class="sbold">Office Address</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Practice Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="practice_name" placeholder="Practice name" value="{{ Input::get('practice_name', @$practice_name) }}">
<!-- START error message -->
{!! $errors->first('practice_name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Street Address <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="street_address" class="form-control" name="street_address" placeholder="Street Address" value="{{ Input::get('street_address', @$street_address) }}">
<!-- START error message -->
{!! $errors->first('street_address','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label">City <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="city" placeholder="City" value="{{ Input::get('city', @$city) }}">
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
                        <input type="text" class="form-control" name="zip_code" placeholder="Zip Code" value="{{ Input::get('zip_code', @$zip_code) }}">
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
                    <label class="col-md-3 control-label">Phone Number <span class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" value="{{ Input::get('phone_number', @$phone_number) }}">
<!-- START error message -->
{!! $errors->first('phone_number','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Doctor's Full Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="doctor_fullname" placeholder="Doctor's Full Name" value="{{ Input::get('doctor_fullname', @$doctor_fullname) }}">
<!-- START error message -->
{!! $errors->first('doctor_fullname','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Office Manager's Full Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="office_manager_full_name" placeholder="Office Manager's Full Name" value="{{ Input::get('office_manager_full_name', @$office_manager_full_name) }}">
<!-- START error message -->
{!! $errors->first('office_manager_full_name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
<!-- END error message -->
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <h4 class="sbold">Practice Types</h4>
                <div class="form-group">
                    <div class="col-md-3">Select all that apply <span class="required">*</span></div>
                    <div class="col-md-9">
                        <div class="row margin-top-10">

                            <?php foreach(practice_types() as $practice_type_k => $practice_type_v): ?>
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
                        <!-- START error message -->
                        {!! $errors->first('practice_type','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    <label class="col-md-3 control-label">Payment Terms</label>
                    <div class="col-md-6">

                    {!! Form::select('payment_terms', payment_terms(), Input::old('payment_terms', @$payment_terms), ["class" => "form-control"]) !!}


                        <span class="help-block text-justify">
                        Tell temporary staff how soon after service you will be able to pay them. Please note that most dental professionals prefer same-day payments. Selecting an option other than "same day" may limit your matching options and success rate.                      
                        </span>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
        <td>
        <label class="mt-checkbox mt-checkbox-outline">

        <input type="checkbox" name="agree" value="1" {{ checked(1, @$agree) }}>
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

        @if($sos)
        <a href="{{ URL::route('auth.register', [1, 'owner']) }}" class="btn green btn-lg btn-step" data-step="1" data-box="2"><i class="fa fa-angle-double-left"></i> Previous</a>
        @else
        <a href="{{ URL::route('auth.register', 2) }}" class="btn green btn-lg btn-step" data-step="2" data-box="3"><i class="fa fa-angle-double-left"></i> Previous</a>
        @endif

        <button type="submit" class="btn blue btn-step btn-lg pull-right"><i class="fa fa-send"></i> Finish</button>
        </div>
    </div>
</div>
<!-- END STEP 3-->