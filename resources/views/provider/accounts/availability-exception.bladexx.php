@extends('layouts.app')

@section('content')


    <h1 class="page-title"> Availability Exception</h1>

    <div class="portlet-body form">

    @include('notification')

        <form method="post" class="form-horizontal">

        {{ csrf_field() }}

            <div class="form-body">


                <div class="form-group">
                    <label class="control-label col-md-2">Form</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control datepicker" name="business_days_from" data-date-format="dd-M-yyyy" 
                        value="{{ Input::old('business_days_from', @$content->business_days_from) }}">
                        <!-- START error message -->
                        {!! $errors->first('business_days_from','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>

                    <div class="col-md-2">

                        {!! Form::select('business_hours_from', get_times(), Input::old('business_hours_from', @$content->business_hours_from), ["class" => "form-control select2"]) !!}

                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-md-2">To</label>
                    <div class="col-md-2">
                   <input type="text" class="form-control datepicker" name="business_days_to" data-date-format="dd-M-yyyy" 
                   value="{{ Input::old('business_days_to', @$content->business_days_to) }}">
                        <!-- START error message -->
                        {!! $errors->first('business_days_to','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                        <!-- END error message -->
                    </div>

                    <div class="col-md-2">
                        {!! Form::select('business_hours_to', get_times(), Input::old('business_hours_to', @$content->business_hours_to), ["class" => "form-control select2"]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Available</label>

                    <div class="col-md-4">
                        <input name="status" type="checkbox" class="make-switch" data-size="small"
                        data-on-text="Yes" 
                        data-off-text="No" 
                        {{ checked(@$info->post_status, 'available') }}>
                    </div>
                </div>


            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-2 col-md-9">
                        <button type="submit" class="btn btn-primary uppercase"><i class="fa fa-check"></i> Save</button>
                        <a href="{{ URL::route('provider.accounts.schedule') }}" class="btn btn-default uppercase">Cancel</a>
                    </div>
                </div>
            </div>
        </form>

    </div>



@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script')
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script> 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>   
@stop

@section('bottom_script')
<script>
$(".select2").select2();     

$(".datepicker").datepicker();     
</script>
@stop
