<?php $form_url = URL::route(Request::segment(1).'.accounts.profile'); ?>

<div class="modal fade in" id="practice_name" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Practice Name</h4>
            </div>
            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <p class="help-block">What is your practice name?</p>
                    <input type="text" name="practice_name" class="form-control" value="{{ @$info->practice_name }}">
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="company_name" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Name of Dental Company</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <input name="company_name" class="form-control" value="{{ @$info->company_name }}">
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="practice_description" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Practice description</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <p class="help-inline no-margin">Tell our service providers about your practice: How long you’ve been in business, what size your practice is, what types of patients you see, etc. And tell them why they would enjoy working in your office.</p>

                <div class="form-group">
                    <textarea name="practice_description" class="form-control" rows="5">{{ @$info->practice_description }}</textarea>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="monthly_referrals" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Monthly referrals</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                 <div class="form-group">
                    <p class="help-block">How many referrals to specialists do you expect to have, per month, on average?</p>
                    <input type="text"name="monthly_referrals" class="form-control" value="{{ @$info->monthly_referrals }}">
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="email_address" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Email Address</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <input type="email" name="email" class="form-control" value="{{ @$info->email }}">
                </div>

                 <div class="form-group">
                    <p class="help-block">Click the button below if you want to receive the confirmation email again.</p>
                    <a href="{{ URL::route('frontend.confirmation') }}" class="btn btn-primary">Resend Email Confirmation</a>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade in" id="gevernment_issued_id" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Valid Government Issued Picture ID</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <label>Attach File</label>
                    <input type="file" name="government_issued_id" class="form-control" accept="image/*">
                    <span class="help-inline">Allowed files: JPEG, JPG and PNG</span>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade in" id="profile_picture" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Profile Picture</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <label>Attach File</label>
                    <input type="file" name="profile_picture" class="form-control" accept="image/*">
                    <span class="help-inline">Allowed files: JPEG, JPG and PNG</span>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade in" id="proof_of_insurance" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Proof of Malpractice Insurance</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <label>Attach File</label>
                    <input type="file" name="proof_of_insurance" class="form-control" accept="image/*">
                    <span class="help-inline">Allowed files: JPEG, JPG and PNG</span>
                    <span class="help-block">Upload your professional malpractice insurance declaration page, and be eligible to work for more offices.</span>

                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>





<div class="modal fade in" id="resume" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Upload Resume</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <div class="form-group">
                    <label>Attach File</label>
                    <input type="file" name="resume" class="form-control" accept=".pdf, .doc, .docx">
                    <span class="help-inline">Allowed files: .pdf, .doc, .docx </span>
                    <span class="help-block">Upload your resume to help potential employers see you, and see your qualifications.</span>

                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="complete_address" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Contact Information</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   
                
                <div class="modal-body">

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ @$info->phone_number }}">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="street_address" class="form-control" value="{{ @$info->street_address }}">
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="{{ @$info->city }}">
                </div>

                <div class="form-group">
                    <label>State</label>

                    {!! Form::select('state', ['' => 'Select State'] + states(),  @$info->state, ["class" => "form-control select2"]) !!}

                </div>
                
                <div class="form-group">
                    <label>Zip code</label>
                    <input type="text" name="zip_code" class="form-control" value="{{ @$info->zip_code }}">
                </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>





<!-- Provider -->
<div class="modal fade in" id="maximum_distance" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Maximum Distance</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <p class="help-inline no-margin">The maximum distance, in miles, you are willing to travel outside of your zip code to perform dental procedures</p>

                <div class="form-group">
                    {!! Form::select('travel_distance', travel_distance(), Input::old('travel_distance', @$info->travel_distance), ["class" => "form-control select2"]) !!}
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="school_info" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">School Information</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                    <div class="form-group">
                        <p class="help-block">School name</p>
                        <input type="text" name="dental_school_name" class="form-control" value="{{ @$info->dental_school_name}}">
                    </div>

                    <div class="form-group">
                        <p class="help-block">Graduation year</p>
                        <input type="text" name="graduation_year" class="form-control" value="{{ @$info->graduation_year}}" maxlength="4">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="dental_license" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Dental License Number</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <p class="help-inline no-margin">We are required to verify that you are licensed to practice in your state</p>

                <div class="form-group">
                    <input type="text" name="dental_license" class="form-control" value="{{ @$info->dental_license }}">
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="professional_objectives" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Professional Objectives</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <p class="help-inline no-margin">Tell offices what you're looking for (e.g., "temp to perm position at a private practice").</p>

                <div class="form-group">
                    <textarea name="professional_objectives" class="form-control" rows="5">{{ @$info->professional_objectives }}</textarea>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="background_description" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Background description</h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <p class="help-inline no-margin">Tell our practice owners about your training, work experience, and any areas of special expertise or interest. And tell them why they would enjoy working with you.</p>

                <div class="form-group">
                    <textarea name="background_description" class="form-control" rows="5">{{ @$info->background_description }}</textarea>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade in" id="minimum_fee" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">What is your hourly rate?</h4>
            </div>


            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <p class="help-inline no-margin">How much do you charge on an hourly basis?</p>

                <div class="form-group">
                    <input type="text" maxlength="6" class="form-control numeric" name="minimum_fee" value="{{ @$info->minimum_fee }}">
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade in" id="contact_info" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">What is your hourly rate?</h4>
            </div>


            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    <div class="form-group">
                        <p class="help-block">State</p>
                        {{ Form::select('state', states(), @$info->state, ['class' => 'form-control select2']) }}
                    </div>

                    <div class="form-group">
                        <p class="help-block">Zip Code</p>
                        <input type="text" name="zip_code" class="form-control" value="{{ @$info->zip_code}}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="interested_in" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                {!! csrf_field() !!}   

                <div class="modal-body">
                    
                <h2 class="text-center">I am interested in</h2>

                
                <div class="form-group margin-top-20">
                        <label class="mt-checkbox mt-checkbox-outline col-md-offset-1">
                        <input type="checkbox" name="permanent_position" value="1" {{ checked(1, @$info->permanent_position) }}> A permanent position in a dental office
                        <span></span>
                        </label>

                        <label class="mt-checkbox mt-checkbox-outline col-md-offset-1">
                        <input type="checkbox" name="temporary_assignments" value="1" {{ checked(1, @$info->temporary_assignments) }}> Temporary assignments in dental offices
                        <span></span>
                        </label>
                </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>




<div class="modal fade in" id="message" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Send a message to <span class="fullname sbold uppercase">{{ Input::old('fullname') }}</span></h4>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ Input::old('action') }}" class="form-submit">
                {!! csrf_field() !!}
                <input type="hidden" name="action" value="{{ Input::old('action') }}">
                <input type="hidden" name="fullname" value="{{ Input::old('fullname') }}">

                <div class="modal-body">
                    
                <div class="form-group">
                    <label>Your Message</label>
                    <textarea name="message" class="form-control" rows="5"></textarea>
                    <!-- START error message -->
                    {!! $errors->first('message','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="no-appointment" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>

                <div class="modal-body">
                    
                    <div class="form-group text-center">
                        <h3>
                        You must confirm at least 1 appointment or job to this person before you able to post your review.
                        </h3>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>

                </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade in" id="owner-trial-period" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>

                <div class="modal-body">
                    
                    <div class="form-group text-center">
                        <h2>Your Account is in <strong>Trial Mode</strong></h2>
                        <h3>
                        Please <a href="{{ URL::route('owner.billings.select_plan') }}" class="sbold">Click Here</a> to purchase Premium Access
                        </h3>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>

                </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade in" id="owner-pending" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>

                <div class="modal-body">
                    
                    <div class="form-group text-center">
                        <h4 class="sbold">Your profile needs to be approved</h4>

                        <p>
                        Your profile needs to be approved to connect with our dental professionals. To ensure prompt approval of your profile, please make sure you have provided all required information, If you believe your profile is complete, and more than 24 hours have passed since you completed your profile, please contact customer support at <a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a>. We are here to support you in your career.</p>
                        </p>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>

                </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade in" id="pending-profile" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ $form_url }}" class="form-submit">
                <div class="modal-body">
                    
                    <div class="form-group text-center">
                        <h4 class="sbold">Your profile needs to be approved</h4>

                        <p>
                        Your profile needs to be approved before applying for this job. To ensure prompt approval of your profile, please make sure you have provided all required information, including your professional license number, hourly rate and background description. If you believe your profile is complete, and more than 24 hours have passed since you completed your profile, please contact customer support at <a href="mailto:{{ App\Setting::get_setting('admin_email') }}">{{ App\Setting::get_setting('admin_email') }}</a>. We are here to support you in your career.</p>
                        </p>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>

                </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
