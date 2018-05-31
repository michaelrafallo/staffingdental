@extends('layouts.app')

@section('content')

<h1 class="page-title">Office Information <small>Edit Office</small></h1>

@include('notification')

<form class="form-horizontal form-submit" method="post" action="" enctype="multipart/form-data">
    <div class="form-body">
        {!! csrf_field() !!}

        <div class="mt-repeater">

                <div data-repeater-list="offices">

                @if( is_array( $offices = Input::get('offices') ) ) 

                @foreach($offices as $office)

                <div data-repeater-item class="mt-repeater-item">
                    <div class="mt-repeater-row">

                        <div class="form-group  margin-top-40">
                            <label class="col-md-4 control-label">
                                Name of Dental Company <span class="required">*</span>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="company_name" class="form-control" value="{{ Input::old('company_name', @$office['company_name']) }}">
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
                                <input type="text" name="street_address" class="form-control" value="{{ @$office['street_address'] }}">
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
                                <input type="text" name="city" class="form-control" value="{{ @$office['city'] }}">
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
                                {!! Form::select('state', ['' => 'Select State'] + states(),  @$office['state'], ["class" => "form-control select2"]) !!}
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
                                <input type="text" name="zip_code" class="form-control" value="{{ @$office['zip_code'] }}">
                                <!-- START error message -->
                                {!! $errors->first('zip_code','<span class="help-block text-danger">:message</span>') !!}
                                <!-- END error message -->
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label"></label>
                            <div class="col-md-7">
                                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete"><i class="fa fa-close"></i> Remove</a>
                            </div>
                        </div>   


                    </div>
                </div>
                @endforeach
                @else
                <div data-repeater-item class="mt-repeater-item">
                    <div class="mt-repeater-row">

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
                                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete"><i class="fa fa-close"></i> Remove</a>
                            </div>
                        </div>   


                    </div>
                </div>
                @endif


                </div>



                
                <div class="form-actions">                
                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add"><i class="fa fa-plus"></i> Add Office</a>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>
                    <a href="{{ URL::route('owner.accounts.settings', ['tab' => 3]) }}" class="btn">Cancel</a>
                </div>      

        </div>



    </div>
</form>


@endsection



@section('top_style')
<style>
.form-actions {
    bottom: 0;
    position: fixed;
    z-index: 9999;
    background: #fff;
    width: 100%;
    padding: 15px 0 15px 20px;
    margin: 0 0 0 -20px;
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>
$(document).on('click', '.mt-repeater-add', function() {
    $('html, body').animate({
        scrollTop: $('.page-footer').offset().top - 50
    }, 1000);  
});
</script>
@stop


