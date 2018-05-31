<form method="post" enctype="multipart/form-data" action="">
    {!! csrf_field() !!}   
    <input type="hidden" name="uid" value="{{ $info->id }}">
    <input type="hidden" name="start_time" class="start-time" value="0">
    <input type="hidden" name="end_time" class="end-time" value="0">
    <input type="hidden" name="date" value="{{ $date }}">
    <div class="book-step step-1">
        <h4 class="modal-title text-center sbold">Book {{ $info->firstname }} for {{ date('D d M Y', strtotime($date)) }}</h4>
        <div class="text-muted uppercase small text-center">1 Hour minimum</div>

        <?php 
        if(@$schedule->post_content) {
            $s = json_decode($schedule->post_content); 

            $time_from = $s->time_from;
            $time_to = $s->time_to;
        } else {
            $s = json_decode($info->schedule, true); 
            $time_from = $s[$day]['from'];
            $time_to = $s[$day]['to'];

        }
        ?>

        <div class="form-group margin-top-20">
            <div class="row col-bordered">
        
                @for ($i=$time_from; $i <= $time_to; $i+=15) 

                <div class="col-md-2 col-sm-2 col-xs-3 col-cell" data-id="{{ $i }}">
                    {{ get_times($i) }}
                </div>

                @endfor

           
            </div>
        </div>

        <div class="form-group text-center form-desc">
            <i class="fa fa-chevron-up text-danger"></i>
            <h4 class="uppercase sbold">Choose <span class="book-mode">start</span> Time</h4>
        </div>
        <div class="form-group text-center form-confirm" style="display:none;">
            START TIME : <strong class="start-time">0</strong> - END TIME : <strong class="end-time">0</strong><br>
            TOTAL : <strong class="book-total">0H 00M($000.00)</strong><br>
            <button class="btn blue-sharp btn-outline btn-circle sbold btn-lg uppercase margin-top-20 move-step" data-target="step-2">Confirm</button>                        
        </div>
    </div>

    <div class="book-step step-2" style="display:none;">
        <div class="text-center">
            <h4 class="no-margin sbold">Booking confirmation</h4>
            <small class="text-muted uppercase">{{ provider_type($info->provider_type) }} {{ $info->firstname }} — {{ date('l, M d, Y', strtotime($date)) }}</small>    
        </div>

        <div class="form-group margin-top-20">
        <table class="table table-bordered">
            <tr>
                <td>
                    <small class="uppercase">Start Time</small><br>
                    <strong class="start-time">0:00 PM</strong>
                </td>
                <td>
                    <small class="uppercase">End Time</small><br>
                    <strong class="end-time">0:00 PM</strong>
                </td>
                <td>
                    <small class="uppercase">Total Time</small><br>
                    <strong class="total-time">0H 00M</strong>
                </td>
                <td>
                    <small class="uppercase">Total Amount</small><br>
                    <strong class="total-amount">$00.0/hr</strong>
                </td>
            </tr>
        </table>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <textarea class="form-control" name="note" rows="5" placeholder="Additional note (optional)"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">Break Time</div>
            <div class="col-md-9">
                {!! Form::select('breaktime', breaktime(), '', ["class" => "form-control"]) !!}
            </div>
        </div>
        <div class="form-group text-center margin-top-40">


        @if( $info->get_meta(Auth::User()->id, 'package_amountx') == 0)
        <a href="#owner-trial-period" class="btn blue-sharp btn-outline btn-circle sbold btn-lg uppercase margin-top-20 hide" data-toggle="modal">Book Appointment</a>
        @endif

        <button class="btn blue-sharp btn-outline btn-circle sbold btn-lg uppercase margin-top-20 book-now">Book Appointment</button>


        </div>
        <div class="form-group text-center margin-top-10">
            <a href="" class="uppercase move-step" data-target="step-1">Change start/End Time</a>
        </div>
    </div>

    <div class="step-3" style="display:none;">
        <div class="form-group row">
            <div class="col-md-12 text-center">
                <h4 class="sbold">Appointment Confirmation</h4>
                <p class="text-justify well">Your work request has been sent to the dental professional. 
                    Please contact the dental professional directly only if this is an urgent staffing request and you need immediate confirmation.
                    Otherwise, we ask that you please extend the dental professional the courtesy of waiting 24 hours for a response.
                    Their name and phone number shown below.
                </p>
                <h4 class="uppercase">{{ $info->fullname }}</h4>
                <i class="fa fa-phone"></i> {{ $info->phone_number }}
            </div>
        </div>
    </div>

</form>
