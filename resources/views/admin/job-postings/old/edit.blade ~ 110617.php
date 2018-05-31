@extends('layouts.app')



@section('content') 

<div class="row margin-top-20">

<div class="col-md-12">
    
    @include('notification')

    <div class="portlet light bordered">
        <h3>Edit job posting</h3> 

        <div class="portlet-body margin-top-30">

        <div class="row">
            <div class="col-md-2 col-md-offset-3">
                <img src="{{ has_photo(App\Usermeta::get_meta($info->post_author, 'profile_picture')) }}" class="img-thumbnail">
            </div>
            <div class="col-md-4">
                <h1 class="no-margin">{{ ucwords($info->user->find($info->post_author)->fullname) }}</h1>
                <h5 class="no-margin margin-top-10">Post Title :</h5> {{ $info->post_title }}
                <h5 class="no-margin margin-top-10">Posted on : </h5>

                {{ date_formatted($info->created_at) }}<br>
                <small>{{ time_ago($info->created_at) }}</small>                        
            </div>            
        </div>

        <form class="form-horizontal margin-top-30" role="form" method="post">

            {!! csrf_field() !!}

            <div class="form-group">
                <label class="col-md-3 control-label">Job Title</label>
                <div class="col-md-8">
                    <input type="text" name="job_title" class="form-control" placeholder="Job Title" value="{{ Input::old('job_title', $info->post_title) }}">
                    <!-- START error message -->
                    {!! $errors->first('job_title','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Job Description</label>
                <div class="col-md-8">
                 <textarea class="form-control" name="job_description" rows="5" placeholder="Job Description">{{ Input::old('job_description',  $info->post_content) }}</textarea>    
                <!-- START error message -->
                {!! $errors->first('job_description','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Type of provider you need</label>
                <div class="col-md-8">
                    {!! Form::select('provider_type', ['' => 'Select provider type'] + provider_type(), Input::old('provider_type', @$info->provider_type), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('provider_type','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Job Type</label>
                <div class="col-md-8">
                    {!! Form::select('job_type', ['' => 'Select job type'] + job_type(), Input::old('job_type', @$info->job_type), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('provider_type','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Salary Type</label>
                <div class="col-md-8">
                    {!! Form::select('salary_type', ['' => 'Select salary type'] + salary_type(), Input::old('salary_type', @$info->salary_type), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('salary_type','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Salary Rate</label>
                <div class="col-md-8">
                    <input type="text" name="salary_rate" class="form-control" placeholder="Salary Rate" value="{{ Input::old('salary_rate', @$info->salary_rate) }}">
                    <!-- START error message -->
                    {!! $errors->first('salary_rate','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Required years of experience</label>
                <div class="col-md-8">
                    {!! Form::select('years_of_experience', ['' => 'Select years of experience'] + years_of_experience(), Input::old('years_of_experience', @$info->years_of_experience), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('years_of_experience','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Practice Type</label>
                <div class="col-md-8">
                    <div class="row">

                        <?php
                        $practice_type = Input::old('practice_type', json_decode(@$info->practice_type));

                        foreach(practice_types() as $practice_type_k => $practice_type_v): ?>
                        <?php 
                            $checked = (isset($practice_type)) ? (in_array($practice_type_k, $practice_type) ? 'checked' : '') : ''; 
                        ?>
                        <div class="col-md-3 col-sm-4 col-xs-6">
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

            @if($info->post_status != 'completed')
            <div class="form-group">
                <label class="col-md-3 control-label">Job Status</label>
                <div class="col-md-8">
                    {!! Form::select('post_status', job_post_status(), Input::old('post_status', @$info->post_status), ["class" => "form-control"]) !!}
                </div>
            </div>
            @endif


            <div class="form-group margin-top-40">
                <div class="col-md-offset-3 col-md-8">
                    <button type="submit" class="btn btn-primary uppercase">Update Job Posting</button>
                    <a href="{{ URL::route('admin.job-postings.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
        
        </div>
    </div>
</div>
</div>
@stop


@section('top_style')

@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')

<script>

</script>
@stop




