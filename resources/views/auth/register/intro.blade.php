@extends('layouts.basic')

@section('content')

<div class="col-md-8 col-centered step-4 step-4 margin-top-10">


    <h3 class="sbold">Welcome to Staffing Dental</h3>

    <h4 class="margin-top-40 well">If you have any questions or concerns please visit <a href="https://staffingdental.com/contact/" target="_blank" class="sbold">staffingdental.com/contact</a></h4>

    <video class="img-responsive" controls="controls"  poster=""> 
       <source src="https://staffingdental.com/wp-content/uploads/staffingdental.3.mp4" type="video/mp4">
       Your browser does not support the video tag.
    </video>

    @if($info->group == 'owner')
        <div class="text-center margin-top-40">
            <a href="{{ URL::route('owner.dentalpro.index') }}" class="btn btn-lg btn-outline blue uppercase btn-block">Continue <i class="fa fa-angle-right"></i></a>  
        </div>
    @endif

    @if($info->group == 'provider')
        <div class="text-center margin-top-40">
            <a href="{{ URL::route('provider.accounts.profile') }}" class="btn btn-lg btn-outline blue uppercase btn-block">Continue <i class="fa fa-angle-right"></i></a>    
        </div>
    @endif

</div>

<div class="margin-top-40"></div>

@stop



@section('top_style')
<style>
.img-responsive {
     width: 100%;
}    
</style>
@stop


@section('bottom_style')
@stop

@section('bottom_plugin_script')
@stop

@section('bottom_script')
@stop




