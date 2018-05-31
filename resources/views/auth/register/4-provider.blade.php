<?php extract($user_data); ?>



<input type="hidden" name="form" value="video">
<input type="hidden" name="user_type_provider" value="provider">
<input type="hidden" name="background_description" value="">

<!-- START STEP 4-->
<div class="col-md-10 col-centered step-4">
    <table class="table table-hover table-striped">
        <tr>
            <td>
                <h4 class="sbold">{{ provider_info($provider_type)->school }}</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{ provider_info($provider_type)->school_name }}</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="dental_school_name" placeholder="{{ provider_info($provider_type)->school_name }}" value="{{ Input::get('dental_school_name', @$dental_school_name) }}">
                        <!-- START error message -->
                        {!! $errors->first('dental_school_name','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Student</label>
                    <div class="col-md-6">
                        <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="student" value="1" data-target="dental_license" class="btn-toggle" {{ checked(1, Input::get('student', @$student)) }}> {{ provider_info($provider_type)->student }}
                        <span></span>
                        </label>
                        <span class="help-inline">If you are a student enter your anticipated graduation year.</span>
                    </div>
                </div>

                <div class="form-group">
                <label class="col-md-3 control-label" style="display:none;">Anticipated graduation year</label>
                <label class="col-md-3 control-label">Graduation Year</label>
                <div class="col-md-6">
                    <input type="text" class="form-control numeric" name="graduation_year" placeholder="Graduation Year" value="{{ Input::get('graduation_year', @$graduation_year) }}" maxlength="4">
                    <!-- START error message -->
                    {!! $errors->first('graduation_year','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </td>
        </tr>
        <tr>
            <td>

                <h4 class="sbold">Rates and Hours</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Fee <span class="required">*</span></label>
                    <div class="col-md-6">
                        <div class="input-inline input-medium">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dollar"></i>
                                </span>
                                <input name="minimum_fee" type="text" maxlength="6" class="form-control numeric" value="{{ Input::get('minimum_fee', @$minimum_fee) }}"> </div>
                        </div>
                        <span class="help-inline"> / hour </span>
                        <!-- START error message -->
                        {!! $errors->first('minimum_fee','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                    <label class="col-md-3 control-label">Minimum Hours</label>
                    <div class="col-md-3">
                        <input type="text" maxlength="6" class="form-control touchspin numeric" name="minimum_hours" value="{{ Input::get('minimum_hours', @$minimum_hours) }}">
                        <!-- START error message -->
                        {!! $errors->first('minimum_hours','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                    <div class="col-md-12">
                    <div class="row">
                        <span class="help-inline col-md-offset-3 col-md-4">
                            What is the minimum number of hours you would like to request, from offices that send you work assignments?
                        </span>                                                
                    </div>                        
                    </div>
                </div>

                <div class="form-group margin-top-10">
                    <div class="row">
                    <label class="col-md-3 control-label">Weekly Hours</label>
                    <div class="col-md-3">
                        <input type="text" maxlength="6" class="form-control touchspin numeric" name="weekly_hours_of_dental_services" value="{{ Input::get('weekly_hours_of_dental_services', @$weekly_hours_of_dental_services) }}">
                        <!-- START error message -->
                        {!! $errors->first('weekly_hours_of_dental_services','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                    <div class="col-md-12">
                    <div class="row">
                        <span class="help-inline col-md-offset-3 col-md-4">
                            For how many hours weekly would you ideally provide dental services through our platform?
                        </span>                                                
                    </div>                        
                    </div>
                </div>


            </td>
        </tr>
        <tr>
            <td>
                <h4 class="sbold">Commute</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Travel distance</label>
                    <div class="col-md-6">
                    <div class="input-inline input-medium">

                    {!! Form::select('travel_distance', travel_distance(), @$travel_distance ? @$travel_distance : 100, ["class" => "form-control select2"]) !!}
                        <!-- START error message -->
                        {!! $errors->first('travel_distance','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                        <span class="help-inline"> / miles </span>
                    <span class="help-inline">
                        The maximum distance you are willing to travel ouside your zipcode to perform dental procedures
                    </span>

                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>

                <div class="form-group">
                    <label class="col-md-3 control-label">Placement Types</label>
                    <div class="col-md-6">
                     <h5 class="sbold">I am interested in:</h5>
                        <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="permanent_position" value="1" {{ checked(1, Input::get('permanent_position', @$permanent_position)) }}> A permanent position in a dental office
                        <span></span>
                        </label>

                        <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="temporary_assignments" value="1" {{ checked(1, Input::get('temporary_assignments', @$temporary_assignments)) }}> Temporary assignments in dental offices
                        <span></span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Select Job Type</label>
                    <div class="col-md-6">
                    {!! Form::select('special_type', ['' => 'Select Job Type'] + special_type(), Input::old('special_type', @$special_type), ["class" => "form-control select2"]) !!}
                    </div>
                </div>


                <div class="form-group hide">
                    <label class="col-md-3 control-label">Skills</label>
                    <div class="col-md-6">

                    {!! Form::select('skills[]', skills(), Input::old('skills', @$skills), ["class" => "form-control select2-multiple", "multiple"]) !!}

                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
        

                <div class="mt-repeater">
                <div class="form-group">
                    <div data-repeater-list="languages">

                        @if( isset($languages) ) 
                        @foreach($languages as $language)
                        <div data-repeater-item class="mt-repeater-item">
                            <div class="row mt-repeater-row">
                                <div class="col-md-6">

                                {!! Form::select('user_languages', [''  => 'Select Language'] + user_languages(), Input::old('user_languages', @$language->user_languages), ["class" => "form-control select2"]) !!}

                                </div>
                                <div class="col-md-5 mt-xs-10">

                                {!! Form::select('fluency',  [''  => 'Select Fluency'] + fluency(), Input::old('fluency', @$language->fluency), ["class" => "form-control select2"]) !!}

                                </div>
                                <div class="col-md-1 ">
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
                                <div class="col-md-6">

                                {!! Form::select('user_languages', [''  => 'Select Language'] + user_languages(), Input::old('user_languages', 'en'), ["class" => "form-control"]) !!}

                                </div>
                                <div class="col-md-5 mt-xs-10">

                                {!! Form::select('fluency', [''  => 'Select Fluency'] + fluency(), 'fluent', ["class" => "form-control"]) !!}

                                </div>
                                <div class="col-md-1">
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

            </td>
        </tr>

        @if(has_dental_license(@$provider_type))
        <tr class="dental_license" style="{{ (@$student) ? 'display:none;' : '' }}">
            <td>
                <h4 class="sbold">Dental License</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Professional License Number</label>
                    <div class="col-md-6">
                        <input type="text" name="dental_license" class="form-control" value="{{ Input::get('dental_license', @$dental_license) }}">
                        <span class="help-inline"><strong>Before admitting a provider into our database</strong>, we are required to verify that you are licensed to practice in your state.</span>                   
                        <!-- START error message -->
                        {!! $errors->first('dental_license','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>
                </div>

            </td>
        </tr>
        @endif

        <tr>
            <td>
                <h4 class="sbold">Upload Resume</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Resume</label>
                    <div class="col-md-6">
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                        <span class="help-inline">Accepted file types : .pdf, .doc, docx</span>                   
                    </div>
                </div>

            </td>
        </tr>

        <tr>
            <td>
                <h4 class="sbold">Proof of Malpractice Insurance (optional)</h4>
                <div class="form-group">
                    <label class="col-md-3 control-label">Insurance Declaration Page</label>
                    <div class="col-md-6">
                        <input type="file" name="proof_of_insurance" class="form-control" accept="image/*">
                        <span class="help-inline">Upload your professional malpractice insurance declaration page, and be eligible to work for more offices.</span>                   
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Expiration date</label>
                    <div class="col-md-6">
                        <input type="text" name="expiry_date" class="form-control datepicker" data-date-format="dd-M-yyyy" readonly="" value="{{ Input::get('expiry_date', @$expiry_date) }}">
                        <span class="help-inline">Please enter the date on which your insurance policy expires. By entering a date, you certify that it is true and correct. Dental offices rely on the accuracy of this information.</span>                   
                    </div>
                </div>

            </td>
        </tr>

    </table>

    <div class="form-group">
        <div class="col-md-12">
        <a href="{{ URL::route('auth.register', [3, 'provider']) }}" class="btn green btn-lg"><i class="fa fa-angle-double-left"></i> Previous</a>
        <button type="submit" class="btn blue btn-lg pull-right"><i class="fa fa-send"></i> Finish</button>
        </div>
    </div>
</div>
<!-- END STEP 4 -->

<style>
.sched-disabled .mt-repeater-row .mt-repeater-delete,
.mt-repeater-item:first-child .mt-repeater-row .mt-repeater-delete {
    display: none;
}
</style>
