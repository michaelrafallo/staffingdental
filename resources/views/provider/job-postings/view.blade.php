@extends('layouts.app')

@section('content')

<?php 
    $status = @$info->where('parent', $info->id)
                    ->where('post_type', 'application')
                    ->where('post_author', $user->id)
                    ->first()
                    ->post_status;

    $selects = array('overall');
    $review_count = App\Post::search([], $selects, [])->where('post_title', $info->post_author)->where('post_type', 'review');
    $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
    $overall_reviews = $review_count->get()->SUM('overall') / $all_reviews_count;
?>


<div class="profile">
    <div class="tabbable-line tabbable-full-width">

        <div class="tab-content">
            <div class="tab-pane active row" id="tab_1_1">
                    <div class="col-md-12">

                        @include('notification')


                        <div class="row margin-top-2">

                            <div class="col-md-12 profile-info">

                                <div class="row">
                                    <div class="col-md-2 hidden-sm hidden-xs">
                                        <div class="thumbnail">
                                            <img src="{{ has_photo($info->get_usermeta($info->post_author, 'profile_picture')) }}" alt="">            
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-12 col-xs-12">
                                        <h2 class="font-green sbold uppercase">{{ $info->post_title }}
                                            @if(@$info->hiring_status)
                                                <span class="badge badge-danger uppercase sbold"> Urgent Hiring <i class="fa fa-exclamation"></i></span>  
                                            @endif
                                        </h2>
                                        <h4><span class="text-muted">Job posted by</span> : {{ ucwords(@$info->user->find($info->post_author)->fullname) }}</h4>
                                     
                                        <h5 class="text-warning">
                                        {{ stars_review($overall_reviews) }}       
                                        </h5>


                                    <div class="margin-top-20 margin-bottom-20">

                                        @if( $info->post_status == 'approved' )

                                            @if($info->where('parent', $info->id)->where('post_type', 'application')->where('post_author', $user->id)->count() == 0)
                                                @if( in_array($user->status, ['approved', 'actived']) )
                                                <a href="#" class="pop-modal btn btn-primary uppercase"
                                                    data-href="{{ URL::route('provider.job-postings.apply', $info->id) }}" 
                                                    data-toggle="modal"
                                                    data-target=".application-modal" 
                                                    data-title="Apply to Job"
                                                    data-body="Write your cover letter below to catch employer's attention.">Apply to Job</a>                    
                                                @else
                                                <a data-toggle="modal" href="#pending-profile" class="btn btn-primary uppercase">Apply to Job</a>
                                                @endif
                                            @else
                                                @if($status == 'waiting')
                                                <a href="#" class="pop-modal btn btn-success uppercase"
                                                    data-href="{{ URL::route('provider.job-postings.apply', $info->id) }}" 
                                                    data-toggle="modal"
                                                    data-target=".application-modal" 
                                                    data-title="Resend Application"
                                                    data-body="Write your cover letter below to catch employer's attention."> Resend Application</a>                                   
                                                @endif
                                            @endif

                                            @if($status != 'waiting')
                                            <a href="{{ URL::route('messages.view', ['provider', $info->post_author]) }}" class="btn uppercase"><i class="icon-bubbles"></i>  Send Message</a>
                                            @endif

                                        @endif                        


                                        <a href="{{ URL::route('provider.job-postings.employer', $info->post_author) }}" class="btn uppercase"><i class="fa fa-user"></i> View Profile</a>

                                        <a href="{{ URL::route('provider.job-postings.index') }}" class="btn  uppercase mt-xs-10"><i class="fa fa-search"></i> Browse more Jobs</a>            

                                        </div>
                        

                                    </div>    
                                </div>


                                <div class="row bg-grey">
                                    <div class="col-md-4">
                                        <i class="icon icon-credit-card pull-left"></i>
                                        <small class="uppercase"><b>SALARY</b><br> 
                                        {{ salary_prefix_formatted($info->salary_type, $info->salary_rate) }}
                                        </small>                                
                                    </div>

                                    <div class="col-md-4">
                                    <i class="icon icon-user pull-left"></i>
                                    <small class="uppercase">
                                    <b>PROVIDER TYPE</b><br>
                                    {{ provider_type($info->provider_type) }}
                                    </small>                                
                                    </div>


                                    <div class="col-md-4">
                                        <i class="icon icon-briefcase pull-left"></i>
                                        <small class="uppercase">
                                        <b>JOB TYPE</b><br>
                                        {{ job_type($info->job_type) }}
                                        </small>
                                    </div>
                                </div>


                                <div class="row bg-grey">
                                    <div class="col-md-4 col-sm-4 mt-xs-10">
                                        <i class="icon icon-calendar pull-left"></i>
                                        <small class="uppercase"><b>Required Experience</b><br> 
                                        {{ years_of_experience($info->years_of_experience) }}
                                        </small>                                
                                    </div>

                                    <div class="col-md-4 col-sm-4 mt-xs-10">
                                        <i class="icon icon-list pull-left"></i>
                                        <small class="uppercase">
                                        <b>Practice types</b><br>
                                        <span class="box">{{ array_to_text(json_decode($info->practice_type), 'practice_types') }}</span>
                                        </small>                                
                                    </div>                                    

                                    <div class="col-md-4 col-sm-4 mt-xs-10">
                                        <i class="icon icon-pin pull-left"></i>
                                        <small class="uppercase"><b>Date Posted</b><br> 
                                        <span class="box">{{ date_formatted($info->created_at) }}<br>
                                        <small class="text-danger">{{ time_ago($info->created_at) }}</small>
                                        <span>
                                        </small>                                
                                    </div>

                                </div>
                                
   
                            </div>

                        </div>

        

                        <div class="portlet light bordered margin-top-20">
                            <div class="portlet-body">

                                @if($status)
                                <div class="alert alert-info application-status"><h5>{!! application_status($status) !!}</h5></div>
                                @endif

                                <div class="row">
                                    <div class="col-md-8 text-justify">
                                    <h4 class="uppercase"> Work Description </h4>
                                        <pre>{{ $info->post_content }}</pre>

                                    </div>
                                    <div class="col-md-4">

                                        <h4 class="uppercase"><i class="fa fa-map-pin text-primary"></i> &nbsp; Office </h4>                                    
                                        <h4>{{ $info->office_name }}</h4>
                                        <p class="text-muted">{{ $info->office_address }}</p>

                                        <h4 class="uppercase"><i class="fa fa-calendar"></i> &nbsp; Work Schedule </h4>
                                        @if( !@$info->schedule && !@$info->working_hours )
                                        <p class="margin-bottom-30">To be discussed</p>
                                        @endif
                                        
                                        @if( @$info->working_hours )
                                        <table class="table table-bordered table-striped table-condensed margin-top-20 margin-bottom-40">
                                        <thead>
                                            <tr>
                                                <th width="120">Day</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                            <?php $availability = json_decode(@$info->working_hours, true); ?>
                                            @foreach(get_days() as $day_k => $day_v)
                                            <?php $adk = @$availability[$day_k]; ?>
                                            <tr>
                                                <td>{{ get_days($day_k) }}</td>
                                                <td>
                                                @if($adk)
                                                    {{ get_times($adk['from']) }} 
                                                    <i class="fa fa-long-arrow-right"></i> 
                                                    {{ get_times($adk['to']) }}
                                                @else
                                                    <b class="text-danger">CLOSED</b>
                                                @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>                                        
                                        @endif

                                        
                                        @if( @$info->schedule )
                                        <table class="table table-bordered table-striped table-condensed margin-top-20">
                                        <thead>
                                            <tr>
                                                <th width="120" class="text-center">Date</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                            @foreach(json_decode($info->schedule) as $sched)
                                            <?php 
                                            $book = $post->select('posts.*', 
                                                    'm1.meta_value as date'
                                                )
                                                ->from('posts')
                                                ->join('postmeta AS m1', function ($join) use ($sched) {
                                                    $join->on('posts.id', '=', 'm1.post_id')
                                                         ->where('m1.meta_key', '=', 'date')
                                                         ->where('m1.meta_value', '=', $sched->date);
                                                })
                                             ->where('post_status', 'confirmed')
                                             ->where('post_type', 'booking')
                                             ->where('post_title', $user->id)
                                             ->first();


                                            $application = $post->where('post_status', 'hired')
                                                         ->where('post_type', 'application')
                                                         ->where('post_author', $user->id)
                                                         ->where('parent', '!=', $info->id)
                                                         ->first();


                                            $schedules = $application ? json_decode($postmeta->get_meta(@$application->parent, 'schedule'), true) : array();
                                            ?>

                                            <tr>
                                                <td class="text-center">{{ date_formatted($sched->date) }}</td>
                                                <td>
                                                    {{ get_times($sched->time_start) }} 
                                                    <i class="fa fa-long-arrow-right"></i> 
                                                    {{ get_times($sched->time_end) }}

                                                    @if($book || in_array( $sched->date, @array_pluck($schedules, 'date')) )
                                                    <br>                     
                                                    <i class="fa fa-calendar-times-o"></i> 
                                                    <a href="{{ URL::route('provider.accounts.schedule', ['date' => $sched->date]) }}#schedule" 
                                                    target="_blank" class="text-danger small uppercase sbold">Conflict Schedule</a>
                                                    @endif

                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        
                                        @endif
                                        
                                    </div>
                                </div>




                            </div>

                        </div>    



                </div>
            </div>


        </div>
    </div>
</div>


@endsection



@section('top_style')

<style>
.application-status  {
    padding: 0px 10px;    
}
pre {
    white-space: pre-wrap;
    word-break: normal;
    background: #fff;
    font-family: "Open Sans",sans-serif;
    font-size: 1em;
    line-height: 1.5;   
    border: 0;
}
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
    padding: 10px 20px 10px 0;
    background: #f3f3f3!important;
    border: 1px solid #fff;
    margin: 0;
}
.bg-grey .box {
    display: -webkit-box;    
}
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
@include('partials.delete-modal')
@stop
