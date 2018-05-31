@extends('layouts.app')

@section('content')



<h1 class="page-title">Office Information <small>Edit Office</small></h1>

@include('notification')

<form class="form-horizontal form-submit" method="post" action="" enctype="multipart/form-data">
    <div class="form-body">
        {!! csrf_field() !!}


            <div class="form-group  margin-top-40">
                <label class="col-md-4 control-label">
                    Name of Dental Company <span class="required">*</span>
                </label>
                <div class="col-md-7">
                    <input type="text" name="company_name" class="form-control" value="{{ Input::old('company_name', @$info->company_name) }}">
                    <!-- START error message -->
                    {!! $errors->first('company_name','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-4 control-label">
                    Address <span class="required">*</span>
                </label>
                <div class="col-md-7">
                    <input type="text" name="street_address" class="form-control" value="{{ @$info->street_address }}">
                    <!-- START error message -->
                    {!! $errors->first('street_address','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                   City <span class="required">*</span>
                </label>
                <div class="col-md-7">
                    <input type="text" name="city" class="form-control" value="{{ @$info->city }}">
                     <!-- START error message -->
                    {!! $errors->first('city','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    State <span class="required">*</span>
                </label>
                <div class="col-md-7">
                    {!! Form::select('state', ['' => 'Select State'] + states(),  @$info->state, ["class" => "form-control select2"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('state','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">
                    Zip code <span class="required">*</span>
                </label>
                <div class="col-md-7">
                    <input type="text" name="zip_code" class="form-control" value="{{ @$info->zip_code }}">
                    <!-- START error message -->
                    {!! $errors->first('zip_code','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>


        <div class="form-group">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-7">
                    <input type="hidden" name="post_status" value="inactived">

                        <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="post_status" value="actived" 
                        {{ Input::get('post_status') }} {{ checked('actived', $info->post_status) }}> Actived Office
                        <span></span>
                        </label>
            </div>
        </div>


        <div class="form-group margin-top-40">
            <div class="col-md-offset-4 col-md-8">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>
                <a href="{{ URL::route('owner.accounts.settings', ['tab' => 3]) }}" class="btn">Cancel</a>
            </div>
        </div>


    </div>
</form>


@endsection



@section('top_style')
<style>

</style>

@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop

@section('bottom_script')
@stop




