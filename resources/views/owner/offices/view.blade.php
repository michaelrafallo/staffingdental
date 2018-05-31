@extends('layouts.app')

@section('content')

<h1 class="page-title">Office Information <small>View Request</small> {{ status_ico($info->post_status) }}  </h1>

@include('notification')

@if( $info->post_status == 'processed' && Input::get('action') != 'edit' || $info->post_status == 'actived' )
<?php $i=1; ?>
@if( is_array( $offices = json_decode($info->post_content, true) ) ) 


<table class="table table-bordered">
<thead>
    <tr>
        <th>Office Address 
        
        @if( $info->post_status == 'processed' )
        <a href="?action=edit" class="pull-right btn"><i class="fa fa-pencil"></i> Edit</a>
        @endif

        <a href="{{ URL::route('owner.accounts.settings', ['tab' => 2]) }}" class="btn pull-right">Cancel</a>

        </th>
        <th>Amount</th>
    </tr>
</thead>
@foreach($offices as $office)
    <tr>
        <td>
        <h5 class="sbold"><span class="text-muted">{{ $i++ }}.</span> {{ @$office['company_name'] }}</h5>
        <p class="no-margin text-muted">{{ $address = ucwords(@$office['street_address'].' '.@$office['city'].' '.states(@$office['state']).' '.@$office['zip_code']) }}</p>
        <a href="https://www.google.com.ph/maps/place/{{ str_replace(' ', '+', $address) }}" class="uppercase small" target="_blank"> View Map</a>                          
        </td>
        <td>
            <h4>{{ amount_formatted(@$office['amount']) }}</h4>
        </td>
    </tr>
    @endforeach
    <tfoot>
        <tr>
            <td>
                @if( $info->post_title ) 
                 <p><b>Notes :</b> {{ ucwords($info->post_title)  }}</p>
                @endif
            </td>
            <td><span class="text-muted">Monthly fee :</span> <h4>{{ amount_formatted($info->post_name) }}</h4></td>
        </tr>
    </tfoot>
</table>
@endif    

@if( $info->post_status == 'processed' )
<form class="form-horizontal form-submit" method="post" action="">
{!! csrf_field() !!}
<label>
<input type="checkbox" name="agree" value="1"> 
I agree to the total amount of <b>{{ amount_formatted($info->post_name) }}</b> monthy fee for additional office address.
</label>
<button class="btn btn-primary">Submit</button>
</form>
@endif

@else
<form class="form-horizontal form-submit" method="post" action="" enctype="multipart/form-data">
    <div class="form-body">
        {!! csrf_field() !!}

        <div class="mt-repeater">

                <div data-repeater-list="offices">

                @if( is_array( $offices = json_decode($info->post_content, true) ) ) 

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
                    <div class="row mt-repeater-row">
                        <div class="col-md-6 col-xs-5">




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


                        </div>
                        <div class="col-md-1 col-xs-1">
                            <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                <i class="fa fa-close"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif


                </div>
                
                <div class="form-actions">                
                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add"><i class="fa fa-plus"></i> Add Office</a>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>

                    @if( $info->post_status == 'processed' )
                    <a href="{{ URL::route('owner.offices.view', $info->id) }}" class="btn">Cancel</a>
                    @else
                    <a href="{{ URL::route('owner.accounts.settings', ['tab' => 3]) }}" class="btn">Cancel</a>
                    @endif

                </div>      


        </div>

    </div>
</form>
@endif  

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




