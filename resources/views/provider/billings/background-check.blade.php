@extends('layouts.app')

@section('content')


<div class="row">
<div class="col-md-8 col-centered">


<h3>Purchase Background Report</h3>

<p class="text-justify">Purchase a background check to add a "background check" icon to your profile, like this one <i class="fa fa-eye text-primary"></i>. Many offices in our network only work with dental professionals who have recent background checks. By purchasing a background check, you will be eligible to work for these offices. And, even for offices that don't require background checks, having one may boost their confidence in your profile and increase your chances of receiving work offers.</p>

<p class="text-justify">By continuing, you are purchasing a Basic Criminal Report from GoodHire.com. This will cost $ 22.50. This is a significantly discounted rate that Staffing Dental has been able to obtain for members like you.</p>


<form class="form-horizontal" method="post">

    <input type="hidden" name="op" value="1">   

    <div class="portlet-body form">


        <h3>Payment details</h3>

        <div class="form-group">
            <label class="col-md-4 control-label">
                Credit card number
            </label>
            <div class="col-md-8">
                <input type="text" name="credit_card_number" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">
                Card code (CVV)
            </label>
            <div class="col-md-8">
                <input type="text" name="credit_card_code" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">
                Card expiration
            </label>
            <div class="col-md-4">
                <select name="credit_card_month" class="form-control">
                    <option value="1">1 - Jan</option>
                    <option value="2">2 - Feb</option>
                    <option value="3">3 - Mar</option>
                    <option value="4">4 - Apr</option>
                    <option value="5">5 - May</option>
                    <option value="6">6 - Jun</option>
                    <option value="7">7 - Jul</option>
                    <option value="8">8 - Aug</option>
                    <option value="9">9 - Sep</option>
                    <option value="10">10 - Oct</option>
                    <option value="11">11 - Nov</option>
                    <option value="12">12 - Dec</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="credit_card_year" class="form-control">
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option>
                    <option value="2031">2031</option>
                    <option value="2032">2032</option>
                </select>
            </div>
        </div>

        
        <div class="form-group">
            <div class="col-md-12">
                <label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="consent" value="1"> 
                I acknowledge receipt of the <a href="">disclosure and consent to a background check</a> which will be provided to Staffing Dental. I agree my electronic signature is the legal equivalent of my manual signature on this Agreement and consent to be legally bound by this Agreement's terms and conditions.
                <span></span>
                </label>
            </div>
        </div>

        <div class="form-actions right">
            <button type="submit" class="btn btn-primary btn-lg">Submit Payment Info</button>
        </div>

    </div>
</form>

</div>

</div>



<div class="modal fade in" id="plan1" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    
                 <div class="form-group text-center">
                    <h1>Basic Access<br>
                    <small class="text-muted">Select your access period</small>
                    </h1>
                 </div>

                 <div class="row text-center">
                    <div class="col-md-3">
                        <h3 class="sbold">30 days</h3>
                        <p class="no-margin sbold text-muted">$60.00 ($2.00/day)</p>    
                        <a href="#" class="btn btn-primary margin-top-10 uppercase choose-plan" data-id="1">Choose</a>              
                    </div>

                    <div class="col-md-3">
                        <h3 class="sbold">90 days</h3>
                        <p class="no-margin sbold text-muted">$162.00 ($1.80/day)</p>    
                        <a href="#" class="btn btn-primary margin-top-10 uppercase choose-plan" data-id="2">Choose</a>              
                    </div>

                    <div class="col-md-3">
                        <h3 class="sbold">180 days</h3>
                        <p class="no-margin sbold text-muted">$288.00 ($1.60/day))</p>    
                        <a href="#" class="btn btn-primary margin-top-10 uppercase choose-plan" data-id="3">Choose</a>              
                    </div>

                    <div class="col-md-3">
                        <h3 class="sbold">360 days</h3>
                        <p class="no-margin sbold text-muted">$504.00 ($1.40/day)</p>    
                        <a href="#" class="btn btn-primary margin-top-10 uppercase choose-plan" data-id="4">Choose</a>              
                    </div>
                </div>


                </div>
                <div class="margin-top-40"></div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
$(document).on('click', '.choose-plan', function(){
  var plan = $(this).data('id');
  $('[name=plan]').val(plan);
  $('.modal').modal('hide');
});  
    
</script>
@stop
