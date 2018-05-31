<?php $user = App\User::find($info->post_author); ?>
<div class="row">
    <div class="col-md-3">
        <img src="{{ has_photo($user->get_meta($info->post_author, 'profile_picture')) }}" class="img-thumbnail">    
    </div>    
    <div class="col-md-9">
        <h3 class="no-margin uppercase">{{ $user->fullname }}</h3>

        <p class="margin-top-10">
        <i class="icon-envelope"></i> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a><br>
        <i class="icon-screen-smartphone"></i> <a href="tel:{{ $user->get_meta($info->post_author, 'phone_number') }}">{{ $user->get_meta($info->post_author, 'phone_number') }}</a>            
        </p>

        <a href="{{ URL::route('provider.job-postings.employer', $info->post_author) }}" class="btn btn-default uppercase">View Profile</a>
        <a href="{{ URL::route('messages.view', ['owner', $info->post_author]) }}" class="btn btn-default uppercase">Message</a>

    </div>    
</div>

<h3>Appointment details: </h3>

<table class="table table-bordered table-striped margin-top-20">
    <tr>
        <td>Date Created</td>
        <td>{{ date_formatted($info->created_at) }}<br> <small class="text-muted">{{ time_ago($info->created_at) }}</small></td>
    </tr>    
    <tr>
        <td width="30%">Appointment Date</td>
        <td>{{ date_formatted($info->date) }}</td>
    </tr>
    <tr>
        <td>Start / End</td>
        <td>{{ get_times($info->start_time) }} - {{ get_times($info->end_time) }}</td>
    </tr>    
    <tr>
        <td>BreakTime</td>
        <td>{{ breaktime($info->breaktime) }}</td>
    </tr>   
    <tr>
        <td>Total Hours</td>
        <td>{{ $info->total_time }}</td>
    </tr>    
    <tr>
        <td>Total Amount</td>
        <td>{{ amount_formatted($info->total_amount) }}</td>
    </tr>    
</table>

@if($info->post_content)
<h4>Note:</h4>
<p class="text-justify no-margin well">
{!! $info->post_content !!}
</p>
@endif

<div class="margin-top-10">
    <hr>

    <a href="javascript:;" class="btn btn-default uppercase" data-dismiss="modal"><i class="fa fa-chevron-left"></i> Back</a>    

    @if($info->post_status == 'pending')

    <a href="#" class="delete btn btn-primary uppercase"
        data-href="{{ URL::route('provider.appointments.status', ['confirmed', $info->id]) }}" 
        data-toggle="modal"
        data-target=".delete-modal" 
        data-title="Confirm Appointment"
        data-body="Are you sure you want to accept appointment with <b>ID# {{ $info->id }}</b>?"><i class="fa fa-thumbs-up"></i> Accept</a>

    @endif

    @if($info->post_status == 'confirmed')

    <a href="#" class="delete btn btn-primary uppercase"
        data-href="{{ URL::route('provider.appointments.status', ['for_approval', $info->id]) }}" 
        data-toggle="modal"
        data-target=".delete-modal" 
        data-title="Confirm Appointment"
        data-body="Are you sure you want to mark appointment as completed with <b>ID# {{ $info->id }}</b>?"><i class="fa fa-check"></i> Completed</a>

    @endif


    @if(in_array($info->post_status, ['pending', 'confirmed']))

    <a href="#" class="delete btn btn-danger uppercase"
        data-href="{{ URL::route('provider.appointments.status', ['cancelled', $info->id]) }}" 
        data-toggle="modal"
        data-target=".delete-modal" 
        data-title="Confirm Appointment"
        data-body="Are you sure you want to cancel appointment with <b>ID# {{ $info->id }}</b>?"><i class="fa fa-times"></i> Cancel</a>
    @endif
</div>
