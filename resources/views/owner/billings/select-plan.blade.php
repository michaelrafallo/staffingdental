@extends('layouts.app')

@section('content')

<div class="row">
<div class="col-md-8 col-centered margin-top-20">

@include('notification')

<div class="text-center">
    <h1>Buy Premium Access</h1>


    <!-- START STEP 1 -->
    <div class="pricing-content-1 margin-top-40">
        <div class="pricing-table-container">
            <div class="row padding-fix">
                <?php $s=1; ?>
                @foreach($rows as $row)
                <?php $post = json_decode($row->post_content);?>
                
                <div class="col-md-6 no-padding col-centered">
                    <div class="price-column-container border-top border-right plan-list">
                        <div class="price-table-head bg-blue">
                            <span class="text-warning">{{ stars_review($s) }}</span>
                            <h2 class="no-margin">{{ $row->post_title }}</h2>
                        </div>
        
                        <div class="arrow-down border-top-blue"></div>
                        <div class="price-table-pricing">
                            <h3>{{ amount_formatted(@$post->total[0]->amount) }}</h3>
                        </div>
                        <div class="price-table-content">
                            @foreach($post->description as $desc)
                            <div class="row no-margin">
                                <div class="col-xs-12 text-center">{!! $desc->list !!}</div>
                            </div>
                            @endforeach          
                        </div>
                        <div class="price-table-footer">
                            <a href="#plan-{{ $row->id }}" data-toggle="modal" class="btn blue btn-circle uppercase buy-access" data-id="3" data-step="2" data-amount="500">Buy {{ $row->post_title }}</a>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="plan-{{ $row->id }}" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <form method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    
                                 <div class="form-group text-center">
                                    <h1>{{ $row->post_title }} Access<br>
                                    <small class="text-muted">Select your access period</small>
                                    </h1>
                                 </div>

                                 <div class="row text-center">
                                    <?php $t=0; ?>
                                    @foreach($post->total as $tot)
                                    <div class="col-md-3">
                                        <h3 class="sbold">{{ $tot->number_of_day }} days</h3>
                                        <p class="no-margin sbold text-muted">{{ amount_formatted($tot->amount) }} ({{ amount_formatted($tot->amount_per_day) }}/day)</p>    
                                        <a href="#" class="btn btn-primary margin-top-10 uppercase choose-plan" data-id="{{ $row->id }}-{{ $t }}" 
                                        data-title="{{ $row->post_title }} Access"
                                        data-total="{{ amount_formatted($tot->amount) }} valid for {{ $tot->number_of_day }} days ">Choose</a>              
                                    </div>
                                    <?php $t++; ?>
                                    @endforeach
                                </div>


                                </div>
                                <div class="margin-top-40"></div>
                            </form>

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <?php $s++; ?>

                <div class="margin-top-40"></div>
                @endforeach

            </div>
        </div>
    </div>
    <!-- END STEP 1 -->


</div>


<form class="form-horizontal" method="post" id="payment">

<div class="well selected-plan margin-top-40" style="{{ Input::old('plan') ? '' : 'display:none;' }}">
    <h3 class="no-margin">{{ Input::old('title')  }}</h3>
    <span class="total">{{ Input::old('total')  }}</span>    
    <a href="" class="btn btn-remove">Remove</a>

    <div class="term-box">
        <label class="mt-checkbox mt-checkbox-outline">
        <input type="checkbox" name="agree" value="1" {{ checked(1, Input::old('agree')) }}>
        I have read, understand and agree to the Staffing Dental <a href="#terms-of-sale" data-toggle="modal">Terms of Sale</a>.
        <span></span>
        </label>
    </div>
        <!-- START error message -->
        {!! $errors->first('agree','<span class="help-block"><span class="text-danger">:message</span></span>') !!}
        <!-- END error message -->        

</div>

    {{ csrf_field() }}

    <input type="hidden" name="total" value="{{ Input::old('total')  }}">
    <input type="hidden" name="title" value="{{ Input::old('title')  }}">

    <input type="hidden" name="plan" value="{{ Input::old('plan')  }}">

    <div class="portlet-body form">
        
        <h3>Payment details</h3>

        <div class="form-group">
            <label class="col-md-4 control-label">
                Credit card number
            </label>
            <div class="col-md-8">


                <div class="input-group">
                    <div class="input-icon">
                        <i class="fa fa-credit-card"></i>
                                <input type="password" name="credit_card_number" class="form-control num" value="{{ Input::old('credit_card_number', @$info->credit_card_number) }}"  maxlength='16'>
                </div>
                    <span class="input-group-btn">
                        <button class="btn btn-success show" type="button" data-target=".num">Show</button>
                    </span>
                </div>

                <!-- START error message -->
                {!! $errors->first('credit_card_number','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">
                Card code (CVV)
            </label>
            <div class="col-md-8">

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                <input type="password" name="credit_card_code" class="form-control code" value="{{ Input::old('credit_card_code', @$info->credit_card_code) }}" maxlength='3'>
                </div>
                    <span class="input-group-btn">
                        <button class="btn btn-success show" type="button" data-target=".code">Show</button>
                    </span>
                </div>

                <!-- START error message -->
                {!! $errors->first('credit_card_code','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">
                Card expiration
            </label>
            <div class="col-md-4">
                {{ Form::select('credit_card_month', getMonths(), Input::old('credit_card_month', @$info->credit_card_month), ['class' => 'form-control']) }}
                <!-- START error message -->
                {!! $errors->first('credit_card_month','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>
            <div class="col-md-4 mt-xs-10">
                {{ Form::select('credit_card_year', get_cc_years(), Input::old('credit_card_year', @$info->credit_card_year), ['class' => 'form-control']) }}
                <!-- START error message -->
                {!! $errors->first('credit_card_year','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
            </div>
        </div>

        <div class="form-actions right">
            <a href="{{ URL::route('owner.billings.index') }}" class="btn uppercase">Cancel</a>
            <button type="submit" class="btn btn-outline btn-circle blue-sharp btn-lg btn-confirm uppercase">Submit Payment Info</button>
        </div>

    </div>


</form>

</div>

</div>



<div class="modal fade bs-modal-lg" id="terms-of-sale" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Terms of Sale</h4>
            </div>
            <div class="modal-body">
                <div class="term-content">
                @include('partials.terms.terms-of-sale')    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection



@section('top_style')
<link href="{{ asset('assets/pages/css/pricing.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.pricing-table-container { padding: 0!important; }    
.price-column-container { padding-top: 0!important; }
.plan-list:hover { background: #e8f2ff!important; }
.price-table-pricing h3 { 
    font-size: 20px!important;
    font-weight: bold;
    margin: 15px 0 15px;
 }
 .pricing-content-1 .plan-list {
    border: 1px solid #e0e0e0;  
 }
 .pricing-content-1 .pricing-table-container .price-column-container>.price-table-content .row:first-child {
    border-top: 1px solid;
    border-color: #eee;
}
.pricing-content-1 .pricing-table-container .price-column-container>.price-table-content .row {
    padding-top: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid;
    border-color: #eee;
}
.pricing-content-1 .price-table-content {
    background-color: transparent;
}
.term-content .text-indent { text-indent: 35px; }  
.term-content ul li { margin-bottom: 10px; }
.term-content {
    height: 300px;
    overflow: auto;
    padding: 20px;
}
.term-box {
    background: #fff;
    display: block;
    padding: 15px 15px 0;    
}
</style>

@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
<script>


$(document).on('click', '.choose-plan', function(){
    var plan = $(this).data('id'), title = $(this).data('title'), total = $(this).data('total');
    $('[name=plan]').val(plan);
    $('.modal').modal('hide');
    $('.btn-confirm').html('<i class="fa fa-check"></i> Proceed to payment');
    $('.selected-plan').show();
    $('.selected-plan h3').html(title);
    $('.selected-plan .total').html(total);
    $('[name=title]').val(title);
    $('[name=total]').val(total);
});  


$(document).on('click', '.btn-remove', function(e){
    e.preventDefault();
    var plan = $(this).data('id');
    $('[name=plan]').val('');
    $('.btn-confirm').html('Update Payment Info');
    $('.selected-plan').hide();
}); 

$(document).on('click', '.show', function(){
    var target = $(this).data('target');

    if( $(this).text() == 'Show' ) {
        $(target).attr('type', 'text');
        $(this).text('Hide');
    } else {
        $(target).attr('type', 'password');
        $(this).text('Show');
    }
});  



</script>
@stop
