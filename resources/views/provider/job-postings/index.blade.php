@extends('layouts.app')

@section('content')


<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"><i class="fa fa-search"></i> Find Work - <small>{{ number_format($count) }} Job{{ is_plural($count) }}</small></h1>
<!-- END PAGE TITLE-->    


@include('notification')

<form method="get" class="form-horizontal margin-top-10">
<div class="form-body">
    <div class="form-group">
        <div class="col-md-3">
            <label class="uppercase small text-muted">Provider Type</label>
            {!! Form::select('provider_type', ['' => 'All'] + provider_type(), Input::get('provider_type'), ["class" => "form-control"]) !!}

        </div>      
        <div class="col-md-3">
            <label class="uppercase small text-muted">Availability</label>
            {!! Form::select('job_type', ['' => 'All'] + job_type(), Input::get('job_type'), ["class" => "form-control"]) !!}
        </div>


        <div class="col-md-2">
            <label class="uppercase small text-muted">Sort By</label>
            {!! Form::select('sort_by', sort_by(), Input::get('sort_by'), ["class" => "form-control"]) !!}
        </div>  
        <div class="col-md-2">
            <label class="uppercase small text-muted">Order By</label>
            {!! Form::select('order_by', order_by(), Input::get('order_by'), ["class" => "form-control"]) !!}
        </div>


    </div>


    <div class="form-group">
        <div class="col-md-3">
            <label class="uppercase small text-muted">Within</label>
            <div class="input-group">
                <input type="text" name="miles" class="form-control" value="{{ Input::get('miles', $info->travel_distance) }}">
                <span class="input-group-addon">
                    miles
                </span>
            </div>
        </div>
        <div class="col-md-2">
            <label class="uppercase small text-muted">Zip Code</label>
            <input type="text" name="zip_code" class="form-control" value="{{ Input::get('zip_code', $info->zip_code) }}">
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-search"></i> Search</button>
        <a href="{{ URL::route('provider.job-postings.index') }}" class="btn">Clear Search</a>
        </div>      
    </div>

</div>
</form>


<?php foreach($rows as $row): 
$postmeta = get_meta($row->postMetas()->get());

$status = @$post->where('parent', $row->id)
                ->where('post_type', 'application')
                ->where('post_author', $user_id)
                ->first()
                ->post_status;

    $selects = array('overall');
    $review_count = App\Post::search([], $selects, [])->where('post_title', $row->post_author)->where('post_type', 'review');
    $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
    $overall_reviews = $review_count->get()->SUM('overall') / $all_reviews_count;

?>
<div class="result">

    <div class="row">
        <div class="col-md-6">

            @if(@$postmeta->hiring_status)
                <span class="label badge-danger uppercase sbold">
                    <i class="fa fa-check"></i>
                    <span class="label uppercase sbold"> Urgent Hiring <i class="fa fa-exclamation"></i> </span>  
                </span>
            @endif


        </div>

        <div class="col-md-12">
            <h4 class="sbold"><a href="{{ URL::route('provider.job-postings.view', $row->id) }}">{{ $row->post_title }}</a></h4>                
            <span class="text-muted">
                <i class="fa fa-user"></i> {{ name_formatted($row->post_author) }}
                
                @if($overall_reviews)
                <i class="fa fa-thumbs-o-up" style="margin-left:15px;"></i> <span class="text-warning">
                {{ stars_review($overall_reviews) }}       
                </span>
                @endif
                
                <i class="fa fa-clock-o" style="margin-left:15px;"></i> <span class="text-muted">{{ time_ago($row->created_at) }}</span>
            </span>
        </div>
    </div>

    <div class="row">    
        <div class="col-md-7">

            <p class="text-justify">{{ str_limit($row->post_content, 300) }} 
            <a href="{{ URL::route('provider.job-postings.view', $row->id) }}" class="btn-xs">READ MORE ...</a>  </p>      
  

            @if($status)
            <p class="alert well"><i class="fa fa-info-circle"></i> {!! application_status($status) !!}</p>
            @endif
            
        </div>
        <div class="col-md-5">

        <table class="table table-bordered table-striped">
            <tr>
                <td width="50%">
                    <b class="small">AVAILABILITY</b><br>
                    {{ job_type($postmeta->job_type) }}
                </td>
                <td>
                    <b class="small">RATE</b><br>
                    {{ salary_prefix_formatted($postmeta->salary_type, $postmeta->salary_rate) }}
                </td>
            </tr>
            <tr>
                <td>
                    <b class="small">PROVIDER TYPE</b><br>
                    {{ provider_type($postmeta->provider_type) }}
                </td>
                <td>
                    <b class="small">WITHIN</b><br>
                    {{ get_distance($row->distance) }}
                </td>
            </tr>
            <tr>
                <td>
                    <b class="small">YEARS OF EXPERIENCE</b><br>
                    {{ years_of_experience($postmeta->years_of_experience) }}
                </td>
                <td>
                    <b class="small uppercase">Practice types</b><br>
                    {{ array_to_text(json_decode($postmeta->practice_type), 'practice_types') }}
                </td>
            </tr>
        </table>
        <div class="btn-group btn-group-justified">

                    @if($row->where('parent', $row->id)->where('post_type', 'application')->where('post_author', $info->id)->count() == 0)
                        @if( in_array($info->status, ['approved', 'actived']) )
                        <a href="#" class="pop-modal btn btn-primary uppercase sbold"
                            data-href="{{ URL::route('provider.job-postings.apply', $row->id) }}" 
                            data-toggle="modal"
                            data-target=".application-modal" 
                            data-title="Apply to Job"
                            data-body="Write your cover letter below to catch employer's attention.">Apply to Job</a>                    
                        @else
                        <a data-toggle="modal" href="#pending-profile" class="btn btn-primary uppercase sbold">Apply to Job</a>
                        @endif
                    @else
                    @if($status == 'waiting')
                    <a href="#" class="pop-modal btn btn-success uppercase sbold"
                        data-href="{{ URL::route('provider.job-postings.apply', $row->id) }}" 
                        data-toggle="modal"
                        data-target=".application-modal" 
                        data-title="Resend Application"
                        data-body="Write your cover letter below to catch employer's attention."> Resend Application</a>   
                    @endif                               
                    @endif
                    
                    <a href="{{ URL::route('provider.job-postings.view', $row->id) }}" class="btn btn-default uppercase"> View Job Details</a>
            </div>
        </div>




    </div>


</div>

<?php endforeach; ?>

<?php parse_str(query_vars(), $appends); ?>
{{ $rows->appends($appends) }}

@if(count($rows) == 0)


@if( $info->lat && $info->lng )
<div class="text-center">
    <h3>No job posting found!</h3> 
    <p>Try broadening your search criteria.</p>     
</div>
@else
    <div class="alert alert-danger"><h4>Your location could not be determined!</h4> Please make sure you give us your correct address.
    <a href="{{ URL::route('provider.accounts.settings', ['tab' => 5]) }}" class="sbold">Edit My Address</a></div> 
@endif

@endif

@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.select2-selection {
  min-height: 34px;
  padding: 3px;
  border: 1px solid #c2cad8!important;
}
.select2-selection__arrow {
  padding: 15px;
}
.sched-box {
    border: 1px solid #d6d4d5;
}
.available {
    background: #ffefae;                
}
.bg-grey .box {
    display: -webkit-box;    
}
.result {
    border: 1px solid #d6d4d5;
    margin-bottom: 25px;
    padding: 20px;
}
.result:hover {
    background: #fbfbfb;    
}

</style>


@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>    

@stop

@section('bottom_script')
<script>
    $(document).on('click', '.btn-filter', function(e) {
    e.preventDefault();
    var form = $('.form-filter');
    if( form.hasClass('show') ) {
        $('.form-filter').hide();                
        form.removeClass('show');
        $('.btn-filter span').text('Show');
        $('[name=filter]').val('hide');
    } else {
        $('.form-filter').show();        
        form.addClass('show');
        $('.btn-filter span').text('Hide');
        $('[name=filter]').val('show');
    }
});

$(".datepicker").datepicker();

$(".select2").select2({width: '100%'});
</script>
@include('partials.delete-modal')
@stop
