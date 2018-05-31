@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Billing Overview</h1>
<!-- END PAGE TITLE-->
@include('notification')

<div class="row">
    <div class="col-md-6">

        <h3>Payment</h3>
        @if($info->credit_card_number)

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                <label class="sbold">Account Number</label><br>
                {{ str_mask( @$info->credit_card_number, 0, 12 ) }}
                </div>                    
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="sbold">CVV</label><br>
                    {{ str_mask(@$info->credit_card_code, 0, 2) }}<br>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="sbold">Expiry Date</label><br>
                    {{ getMonths(@$info->credit_card_month) }}
                    {{ get_cc_years(@$info->credit_card_year) }}            
                </div>                    
            </div>
        </div>
        <a href="{{ URL::route('owner.billings.select_plan') }}#payment" class="btn btn-primary uppercase margin-top-10">Change Credit Card</a>

        @else
        <h4 class="no-margin margin-top-10"><i class="fa fa-credit-card"></i> No credit card</h4>
        <a href="{{ URL::route('owner.billings.select_plan') }}#payment" class="btn btn-primary uppercase margin-top-10">Add Credit Card</a>
        @endif


    </div>    

    <div class="col-md-6">


        <h3>Plan</h3>
    
        @if( @$plan->id && @$plan->post_status=='paid')                 
            <h3 class="no-margin">{{ $plan->get_meta($plan->id, 'title') }} Access</h3>
            <h4>{{ amount_formatted($plan->get_meta($plan->id, 'amount')) }}
            for 30 days access valid until 
            {{ $access_expiry_date = date_formatted($plan->get_meta($plan->id, 'access_expiry_date')) }} </h4>               


            <div class="clearfix"></div>
            <a class="btn red btn-outline sbold uppercase margin-top-10" data-toggle="modal" href="#cancel"> Cancel Subscription </a>


            <div class="modal fade" id="cancel" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">End Subscription</h4>
                        </div>
                        <div class="modal-body">

                        <form action="{{ URL::route('stripe.subscription.cancel') }}" method="get">

                            <div class="mt-radio-list">
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="at_period_end" value="false" checked=""> 
                                    <h5 class="sbold">Immediately</h5>
                                    End the subscription immediately.
                                    <span></span>
                                </label>
                                <label class="mt-radio mt-radio-outline">
                                    <input type="radio" name="at_period_end" value="true">
                                    <h5 class="sbold">At period end ({{ $access_expiry_date }})</h5>
                                    End the subscription at the end of the current billing period.        
                                    <span></span>
                                </label>
                            </div>


                            <button type="submit" class="btn btn-primary">End Subscription</button>
                            
                        </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>


        @else
            <h4 class="no-margin margin-top-10">You must select a plan to start booking appointments</h4>
        @endif

        <a href="{{ URL::route('owner.billings.select_plan') }}" class="btn btn-primary uppercase margin-top-10">Change Plan</a>     

        
    </div>
</div>




<h3 class="margin-top-40">Payment History</h3>

<div class="table-responsive">
<table class="table">
    <thead>
        <th>Reference No.</th>
        <th>Details</th>
        <th>Status</th>
        <th>Total</th>
        <th>Date</th>   
    </thead>
    <tbody>
        @foreach($rows as $row)
        <?php $postmeta = get_meta($row->postMetas()->get()); ?>
        <tr>
            <td>{{ @$postmeta->reference_no }}</td>
            <td>{{ $row->post_title }}</td>
            <td>
            {{ status_ico($row->post_status) }}
            @if(@$postmeta->package_ended)
            <br>
            <small>{{ date_formatted($postmeta->package_ended) }}</small>
            @endif

            </td>
            <td>{{ amount_formatted(@$postmeta->amount) }}</td>
            <td>{{ date_formatted($row->created_at) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

@if(count($rows)==0)
<h3 class="text-center">No payment history at this moment, <a href="{{ URL::route('owner.billings.select_plan') }}">Select a Plan.</a></h3>
@endif

{{ $rows->links() }}

@endsection



@section('top_style')
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
@stop
