@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Engagement Points</h1>
<!-- END PAGE TITLE-->

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"> Last 30 days points </div>
            <div class="panel-body"><h1 class="no-margin">{{ number_format($engage_30_days) }}</h1></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"> Last 7 days points </div>
            <div class="panel-body"><h1 class="no-margin">{{ number_format($engage_7_days) }}</h1></div>
        </div>
    </div>  
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"> Total points </div>
            <div class="panel-body"><h1 class="no-margin">{{ number_format(@$info->engage_points) }}</h1></div>
        </div>
    </div>
</div>

<p>Earn reward points to increase your Level on Staffing Dental. Learn more here. Each Level earns you a different badge on your profile for offices to see.</p>

<div class="row">
    <div class="col-md-4 text-center">
    <img src="{{ asset('img/level-1.png') }}" class="img-badge">
    <h3 class="sbold">Level 1</h3> 
    <h4>Earn 50 points, complete 5 appointments and keep a 4 star rating or higher.</h4>
    </div> 

    <div class="col-md-4 text-center">
    <img src="{{ asset('img/level-2.png') }}" class="img-badge">
    <h3 class="sbold">Level 2</h3> 
    <h4>Earn 200 points, complete 20 appointments and keep a 4 star rating or higher.</h4>
    </div> 

    <div class="col-md-4 text-center">
    <img src="{{ asset('img/level-3.png') }}" class="img-badge">
    <h3 class="sbold">Level 3</h3> 
    <h4>Earn 1000 points, complete 100 appointments and keep a 4 star rating or higher.</h4>
    </div> 
</div>


<h3>Referral Points</h3>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"> Last 30 days points </div>
            <div class="panel-body"><h1 class="no-margin">{{ number_format($referral_30_days) }}</h1></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"> Last 7 days points </div>
            <div class="panel-body"><h1 class="no-margin">{{ number_format($referral_7_days) }}</h1></div>
        </div>
    </div>  
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"> Total points </div>
            <div class="panel-body"><h1 class="no-margin">{{ number_format($info->reward_points) }}</h1></div>
        </div>
    </div>
</div>



<h3>Referred Dental Professionals ({{ number_format(count($providers)) }})</h3>
<table class="table datatable">
    <thead>
        <tr>
            <th>ID#</th>
            <th>User Name</th>
            <th>Provider Type</th>
            <th>Profile completition date</th>
            <th>Referral points</th>
            <th>Status</th>
            <th>Date Registered</th>
        </tr>
    </thead>
    <tbody>
        @foreach($providers as $provider)
        <?php $postmeta = get_meta(@$provider->userMetas()->get()); ?>
        <tr>
            <td>{{ $provider->id }}</td>
            <td>{{ $provider->fullname }}</td>
            <td>{{ provider_type(@$postmeta->provider_type) }}</td>
            <td></td>
            <td>{{ number_format($provider->get_meta($provider->id, 'referrer_points')) }}</td>
            <td>{{ status_ico($provider->status) }}</td>
            <td>{{ date_formatted($provider->created_at) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Referred Dental Offices ({{ number_format(count($owners)) }})</h3>
<table class="table datatable">
    <thead>
        <tr>
            <th>ID#</th>
            <th>Office Name</th>
            <th>Date plan purchased</th>
            <th>Referral points</th>
            <th>Status</th>
            <th>Date Registered</th>
        </tr>
    </thead>
    <tbody>
        @foreach($owners as $owner)
        <?php $postmeta = get_meta($owner->userMetas()->get()); 

            $plan = App\Post::where('post_name', 'subscription_plan')
                                 ->where('post_type', 'payment')
                                 ->where('post_author', $owner->id)
                                 ->orderBy('id', 'ASC')
                                 ->first();
        ?>
        <tr>
            <td>{{ $owner->id }}</td>
            <td>{{ $owner->fullname }}</td>
            <td>{{ date_formatted(@$plan->created_at) }}</td>
            <td>{{ number_format(@$owner->get_meta($owner->id, 'referrer_points')) }}</td>
            <td>{{ status_ico($owner->status) }}</td>
            <td>{{ date_formatted($owner->created_at) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style>
table.dataTable.no-footer {
    border-bottom-color: #e7ecf1;   
}   
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>
$('.datatable').dataTable({
    "order": [[ 0, "desc" ]]
});
</script>
@stop
