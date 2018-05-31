@extends('layouts.app')

@section('content')
<div class="profile">
    <div class="tabbable-line tabbable-full-width">
        <div class="tab-content">

            @include('notification')

            <div class="tab-pane active" id="tab_1_1">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-unstyled profile-nav">
                            <li>
                               <img src="{{ has_photo($info->profile_picture) }}" class="img-thumbnail">
                            </li>
                            
                            @if( $info->get_meta($info->id, 'package_amount') )
                            <li><a href="{{ URL::route('messages.view', ['owner', $info->id]) }}">Go to messenger</a></li>
                            @endif

                        </ul>
                        <div class="text-center">

                            @if( $info->get_meta($info->id, 'package_amount') )
                            <a href="#message" class="btn btn-primary btn-sm btn-block uppercase send-msg" data-toggle="modal" href="#message" 
                            data-url="{{ URL::route('messages.sent', ['owner', $info->id]) }}"
                            data-fullname="{{ $info->fullname }}">Message</a>
                            @endif

                            <div class="text-warning margin-top-10 h4">
                                {{ stars_review($overall_reviews) }}           
                            </div>
                            <small class="uppercase">{{ $all_reviews_count }} review{{ is_plural($all_reviews_count) }}</small>
                        </div>


                        <p>
                            <small class="sbold">LAST LOGIN</small><br>
                            {{ time_ago($info->last_login) }}
                        </p>

                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 profile-info">                            
                                <h1 class="font-green sbold uppercase">{{ $info->fullname }}</h1>
                                <h4>
                                @if($info->company_name)
                                {{ $info->company_name }}
                                @else
                                Dental Owner
                                @endif
                                </h4>
                            </div>
                        </div>
 


                        <div class="row margin-top-20">
                            <div class="col-md-5">
                            <i class="icon icon-pointer pull-left"></i>
                            <small class="uppercase">
                                <strong>LOCATED IN </strong><br>
                                {{ $info->city }} {{ $info->state ? states($info->state) : '' }}
                            </small>                                
                            </div>

                            <div class="col-md-3">
                                <i class="icon icon-clock pull-left""></i>
                                <small class="uppercase">
                                {{ number_format($appointments) }} Completed<br>
                                <strong class="uppercase">Appointments</strong>
                                </small>
                            </div>

                            <div class="col-md-3">
                                <i class="icon icon-users pull-left""></i>
                                <small class="uppercase">
                                {{ number_format($hired) }} Hired<br>
                                <strong class="uppercase">Professionals</strong>
                                </small>
                            </div>

                            

                        </div>

        

                        <div class="row margin-top-20 bg-grey">
                            <div class="col-md-3">
                                <i class="icon icon-wallet pull-left""></i>
                                <small class="uppercase">Payment Terms<br>
                                <strong class="uppercase">{{ @payment_terms($info->payment_terms) }}</strong>
                                </small>
                            </div>
                        </div>

                        <div class="portlet light bordered margin-top-20">

                            <div class="portlet-body">



                                <h4 class="sbold">Practice Name
                                </h4>
                                <p>{{ $info->practice_name }}
                                </p>

                                <h4 class="sbold">
                                    Practice Description 
                                </h4>


                                <p class="text-justify">{{ $info->practice_description }}</p>

                                <h4 class="sbold">Office Details
                                </h4>
                                <p>
                                @if($info->office_contact_name)
                                <i class="fa fa-home"></i> {{ $info->office_contact_name }}<br>
                                @endif

                                @if($info->office_contact_email)
                                <i class="fa fa-envelope-o"></i> <a href="mailto:{{ $info->office_contact_email }}">{{ $info->office_contact_email }}</a><br>
                                @endif

                                @if($info->office_contact_phone)
                                <i class="fa fa-phone"></i> <a href="tel:{{ $info->office_contact_phone }}">{{ $info->office_contact_phone }}</a>
                                @endif
                                </p>



                                <hr>

                                <?php 
                                $address = array(
                                  @$info['street_address'],
                                  @$info['city'],
                                  @$info['state'],
                                  @$info['zip_code'],
                                  'US'
                                );

                                $address = implode(' ', $address);
                                ?>


                                <h4 class="sbold">Email Address</h4>
                                <p class="text-justify">{{ $info->email }}</p>                                

                                <h4 class="sbold">Contact Information </h4>
                                <i class="fa fa-phone"></i> <a href="tel:{{ $info->phone_number }}">{{ $info->phone_number }}</a><br>
                                <i class="fa fa-map-marker"></i> {{ $info->street_address }} {{ $info->city }} {{ $info->state }} {{ $info->zip_code }}
                                </p>

                                <iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q={{ urlencode($address) }}&amp;output=embed"></iframe>
              


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('partials.review')

<div class="modal fade in" id="book" tabindex="-1" role="basic" aria-hidden="true" data-uid="{{ $info->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>

            </div>

            <div class="modal-body"></div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



@endsection



@section('top_style')
<link href="{{ asset('assets/pages/css/profile-2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.icon {
    color: rgba(0,0,0,0.4);
    margin: 13px 10px 0;
    font-size: 2.1em;
}

.img-review {
    margin: 13px 10px 0;    
    width: 80px;
}

.bg-grey {
    padding: 15px 20px 15px 20px;
    background-color: #f1f4f7;    
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/fullcalendar/fullcalendar.min.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>
$(document).on('click', '.btn-review', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    $('.form-review').hide();
    $('.'+target).show();
});


$(document).on('click keyup', '.review', function() {
    var checked = $('input.review:checked').length;
    if( checked == 1 ) {
        $('.submit-review').removeAttr('disabled');            
    } else {
        $('.submit-review').attr('disabled', 'disabled');                        
    }

});
</script>

@include('partials.delete-modal')
@stop
