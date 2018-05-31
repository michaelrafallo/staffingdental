@extends('layouts.app')

@section('content')
<div class="col-md-8 col-sm-8 col-centered margin-top-40">
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject uppercase">Edit Plan</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" role="form" method="post">

                {{ csrf_field() }}

                <div class="form-body">
                  
                    <div class="form-group">
                        <label class="col-md-3 control-label">Plan Title</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="title" placeholder="Plan Title" value="{{ $info->post_title }}">
                            <!-- START error message -->
                            {!! $errors->first('title','<span class="help-block"><p class="text-danger">:message</p></span>') !!}
                            <!-- END error message -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Description Lists</label>
                        <div class="col-md-8">

                            <div class="mt-repeater">
                                <div data-repeater-list="description">
                                    @if( $description )
                                        @foreach( $description as $desc )
                                        <div data-repeater-item class="mt-repeater-item">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="list" placeholder="Enter Description here" value="{{ $desc->list }}"> 
                                                <span class="input-group-btn">

                                                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                                <i class="fa fa-close"></i>

                                                </a>
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                    <div data-repeater-item class="mt-repeater-item">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="list" placeholder="Enter Description here" value=""> 
                                            <span class="input-group-btn">

                                            <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                            <i class="fa fa-close"></i>

                                            </a>
                                            </span>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                <a href="javascript:;" data-repeater-create class="btn btn-success btn-sm mt-repeater-add uppercase">
                                <i class="fa fa-plus"></i> Add Desciption</a>
                            </div>


                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-3">


                            <div class="mt-repeater">
                                <div data-repeater-list="total">

                                    @if( $total )
                                        @foreach( $total as $tot )
                                        <div data-repeater-item class="mt-repeater-item">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="small">Amount per Day</label>
                                                    <div class="input-icon">
                                                        <i class="fa fa-dollar"></i>
                                                        <input type="text" name="amount_per_day" class="form-control day day-amount" value="{{ $tot->amount_per_day }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small">Number of Days</label>
                                                    <div class="input-group">
                                                        <input type="text"name="number_of_day" class="form-control day day-number" value="{{ $tot->number_of_day }}"> 
                                                        <span class="input-group-btn">
                                                        <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                                        <i class="fa fa-close"></i>
                                                        </a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="amount" class="amount" value="{{ $tot->amount }}">
                                                <div class="col-md-4"><h3 class="text-right">$<span class="period-total">{{ $tot->amount }}</span></h3></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                    <div data-repeater-item class="mt-repeater-item">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="small">Amount per Day</label>
                                                <div class="input-icon">
                                                    <i class="fa fa-dollar"></i>
                                                    <input type="text" name="amount_per_day" class="form-control day day-amount">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small">Number of Days</label>
                                                <div class="input-group">
                                                    <input type="text"name="number_of_day" class="form-control day day-number"> 
                                                    <span class="input-group-btn">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                                    <i class="fa fa-close"></i>
                                                    </a>
                                                    </span>
                                                </div>
                                            </div>
                                                <input type="hidden" name="amount" class="amount" value="">

                                            <div class="col-md-4"><h3 class="text-right">$<span class="period-total">0.00</span></h3></div>
                                        </div>
                                    </div>
                                    @endif

                                </div>
                                <a href="javascript:;" data-repeater-create class="btn btn-sm btn-success mt-repeater-add uppercase">
                                <i class="fa fa-plus"></i> Add Access Period</a>
                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Status</label>
                        <div class="col-md-8">
                            <input type="checkbox" name="status" class="make-switch" 
                            data-size="small" 
                            data-on-color="success" 
                            data-on-text="ACTIVED" 
                            data-off-color="default" 
                            data-off-text="INACTIVED" 
                            {{ checked($info->post_status, 'actived') }}>
                        </div>
                    </div>
                </div>

                <div class="form-actions right">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-9">
                            <a href="{{ URL::route('admin.plans.index') }}" class="btn">Cancel</a>
                            <button type="submit" class="btn btn-primary uppercase btn-circle"><i class="fa fa-check"></i> Update Plan</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>




@endsection



@section('top_style')
<style>
.mt-repeater-delete {
    margin: 0!important;
}
.mt-repeater-item:first-child .input-group .input-group-btn {
    display: none;
}
.mt-repeater-item:first-child .input-group {
    width: 100%;
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
$(document).on('keyup', '.day', function() {
    var a = $(this).closest('.mt-repeater-item').find('.day-amount').val();
    var b = $(this).closest('.mt-repeater-item').find('.day-number').val();
    var total = parseFloat(a * b).toFixed(2);

    $(this).closest('.mt-repeater-item').find('.period-total').html(total);
    $(this).closest('.mt-repeater-item').find('.amount').val(total);

});


$(".mt-repeater").each(function() {
    $(this).repeater({
        initEmpty: true,
        show: function(e) {
           var index = $('.mt-repeater-item').length -1;
           $(this).slideDown(function(){
       
           });
        },
        hide: function(e) {
           // var url = $(this).find('a.mt-repeater-delete').data('href');
           $(this).slideUp(e)
        },
        ready: function(e) {     
        }
    })
});    

function str_random(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz,randomPoz+1);
    }
    return randomString;
}
</script>
@stop
