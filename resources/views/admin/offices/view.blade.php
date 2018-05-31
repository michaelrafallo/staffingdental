@extends('layouts.app')

@section('content')

<h1 class="page-title">Office Information </h1>

@include('notification')

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-2 text-center">
                <img src="{{ has_photo($user->profile_picture) }}" class="img-thumbnail">                
            </div>
            <div class="col-md-10">
                <h4><a href="{{ Route('admin.users.edit', $info->id) }}" target="_blank">{{ $user->firstname.' '.$user->lastname }}</a></h4>     
                <h5>( <b>{{ count($offices) + 1 }}</b> ) active office {{ (count($offices) + 1) > 1 ? str_plural('address') : 'address' }}</h5>   
            </div>            
        </div>

        <div class="margin-top-20">
            <p class="no-margin text-muted">{{ $address = ucwords($user->street_address.' '.@$user->city.' '.states(@$user->state).' '.@$user->zip_code) }}</p>
            <a href="https://www.google.com.ph/maps/place/{{ str_replace(' ', '+', $address) }}" class="uppercase small" target="_blank"> View Map</a>
        </div>


        <div class="margin-top-20">
        <?php $i=1; ?>
        @foreach($offices as $office)
            <h5 class="sbold"><span class="text-muted">{{ $i++ }}.</span> {{ ucwords(@$office['post_title']) }}</h5>
            <p class="no-margin text-muted">{{ @$office->post_name }}</p>
            <a href="https://www.google.com.ph/maps/place/{{ str_replace(' ', '+', $office['post_title']) }}" class="uppercase small" target="_blank"> View Map</a>
            <hr>
        @endforeach            
        </div>

    </div>
    <div class="col-md-6">
    <?php $requests = json_decode($info->post_content, true); ?>

    <h4>( <b>{{ count($requests) }}</b> ) New Requested Office {{ count($requests) > 1 ? str_plural('Address') : 'Address' }}
    <a href="{{ URL::route('admin.offices.edit', $info->id) }}" class="btn uppercase"><small><i class="fa fa-pencil"></i> Edit Request</small></a></h4>

    <form class="form-horizontal form-submit" method="post" action="" enctype="multipart/form-data">
        {!! csrf_field() !!}

        <div class="margin-top-20">
        <?php $i=1; ?>
        @if( is_array( $requests ) ) 
        @foreach($requests as $request)
        <div class="row">               
            <div class="col-md-9">
                <h5 class="sbold"><span class="text-muted">{{ $i++ }}.</span> {{ @$request['company_name'] }}</h5>
                <?php $state = @$request['state'] ? states(@$request['state']) : ''; ?>
                <p class="no-margin text-muted">{{ $address = ucwords(@$request['street_address'].' '.@$request['city'].' '.$state.' '.@$request['zip_code']) }}</p>
                <a href="https://www.google.com.ph/maps/place/{{ str_replace(' ', '+', $address) }}" class="uppercase small" target="_blank"> View Map</a>              
            </div>
            <div class="col-md-3">
                <div class="input-icon">
                    <i class="fa fa-dollar font-blue"></i>
                    <input type="text" name="amount[]" class="form-control amount numeric" value="{{ @$request['amount'] }}">  
                </div>            
            </div>
        </div>
        <hr>
        @endforeach
        @endif    
        </div>

        <div class="form-body">

            <div class="pull-right">
                <div class="col-md-12">
                    <h5>Amount</h5>
                    <h4><i class="fa fa-dollar font-blue"></i>
                    <span class="total-amount  sbold">{{ $info->post_name }}</span></h4>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <h5>Add Note</h5>
                    <textarea class="form-control" name="notes" rows="5">{{ $info->post_title }}</textarea>
                </div>
            </div>

            <div class="form-actions">                
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Set Quote</button>
                <a href="{{ URL::route('admin.offices.index') }}" class="btn">Cancel</a>
            </div>      
        </div>
    </form>    
    </div>    
</div>

@endsection



@section('top_style')
<style>

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

$(document).on('keyup', '.amount', function() {
var total = 0;

$('.amount').each(function() {
    total += Number( $(this).val() );
});
$('.total-amount').html(total.toFixed(2));

});

</script>
@stop




