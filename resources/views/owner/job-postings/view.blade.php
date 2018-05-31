@extends('layouts.app')

@section('content')


<div class="profile">
    <div class="tabbable-line tabbable-full-width">

        <div class="tab-content">
            <div class="tab-pane active row" id="tab_1_1">
                    <div class="col-md-12">

                        @include('notification')

                        <div class="row margin-top-2">



                            <div class="col-md-12 profile-info margin-top-10">


                            <a href="{{ URL::route('owner.job-postings.edit', $info->id) }}" class="btn btn-success uppercase">Edit Job posting</a> 
                            <a href="{{ URL::route('owner.job-postings.index') }}" class="btn uppercase"><i class="fa fa-long-arrow-left"></i> All Jobs</a>                                    

                            <h2 class="font-green sbold uppercase">{{ $info->post_title }}
                                @if(@$info->hiring_status)
                                    <span class="badge badge-danger uppercase sbold">Urgent Hiring <i class="fa fa-exclamation"></i></span>  
                                @endif
                            </h2>

                                <div class="row margin-top-20 bg-grey">
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


                                <div class="row">
                                    <div class="col-md-8 text-justify">
                                        <pre>{{ $info->post_content }}</pre>
                                    </div>
                                    <div class="col-md-4">
                                                                            
                                        <h4 class="uppercase"><i class="fa fa-map-pin text-primary"></i> &nbsp; Office </h4>                                    
                                        <h4>{{ $info->office_name }}</h4>
                                        <p class="text-muted">{{ $info->office_address }}</p>

                                        <h4 class="uppercase margin-top-40"><i class="fa fa-calendar"></i> &nbsp; Work Schedule </h4>
                                        @if( !@$info->schedule && !@$info->working_hours )
                                        <p class="margin-bottom-30">To be discussed</p>
                                        @endif

                                        @if( @$info->schedule )
                                        <table class="table table-bordered table-striped table-condensed margin-top-20 margin-bottom-40">
                                        <thead>
                                            <tr>
                                                <th width="120" class="text-center">Date</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                            @foreach(json_decode($info->schedule) as $sched)
                                            <tr>
                                                <td class="text-center">{{ date_formatted($sched->date) }}</td>
                                                <td>
                                                    {{ get_times($sched->time_start) }} 
                                                    <i class="fa fa-long-arrow-right"></i> 
                                                    {{ get_times($sched->time_end) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>                                        
                                        @endif

                                        @if( @$info->working_hours )
                                        <table class="table table-bordered table-striped table-condensed margin-top-40 margin-bottom-40">
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

                                      <h4 class="uppercase"><i class="fa fa-briefcase"></i> &nbsp; Job Application Status </h4>
                                        <hr>

                                        <table width="100%">
                                            <tr>
                                                <td width="60" class="sbold">Applied</td>
                                                <td> : {{ number_format($post->where('parent', $info->id)->count()) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="sbold">Slot</td>
                                                <td> : {{ $info->number_of_position }} / {{ number_format($post->where(['post_type' => 'application', 'post_status' => 'hired', 'parent' => $info->id])->count()) }}</td>
                                            </tr>
                                        </table>


                                    </div>
                                </div>

                            </div>


                            @if(count($rows) > 0)
                            <h3 class="margin-top-40"> Applicants ({{ count($rows) }})</h3>
                            <table class="table table-hover table-stripe table-condensed table-striped datatable select-disabled">
                                <thead>
                                    <tr>
                                        <th>ID#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Date Applied</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $row)
                                    <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>
           
                                    <?php $user = App\User::find($row->post_author); ?>

                                   <img src="{{ has_photo($user->get_meta($row->post_author, 'profile_picture')) }}" class="img-thumbnail">

                                    {!! profile_status($user->get_meta($row->post_author, 'availability')) !!} <a href="{{ URL::route('owner.dentalpro.profile', [$row->post_author, 'job_id' => $info->id]) }}"  class="uppercase">
                                        {!! name_formatted($user->id) !!}
                                    </a>   

                                    <div class="margin-top-10">                                    
                                        @if( in_array($row->post_status, ['cancelled', 'waiting']) )
                                        <a href="#" class="pop-modal btn green-sharp btn-xs btn-outline uppercase"
                                            data-href="{{ URL::route('owner.job-postings.hire', ['invited', $row->id, $row->post_author]) }}" 
                                            data-toggle="modal"
                                            data-target=".ask-modal" 
                                            data-title="Invite <b>{{ $user->fullname }}</b> for interview"
                                            data-val="{{ @App\Setting::get_setting('applicant_invite') }}">Invite</a>
                                        @endif

                                        @if( in_array($row->post_status, ['cancelled', 'waiting', 'invited']) )
                                        <a href="#" class="pop-modal btn btn-primary btn-xs uppercase"
                                            data-href="{{ URL::route('owner.job-postings.hire', ['hired', $row->id, $row->post_author]) }}" 
                                            data-toggle="modal"
                                            data-target=".ask-modal" 
                                            data-title="Hire <b>{{ $user->fullname }}</b>"
                                            data-val="{{ @App\Setting::get_setting('applicant_hire') }}"><i class="fa fa-check"></i> Hire</a>
                                        @endif

                                        @if( $row->post_status != 'cancelled' )
                                        <a href="#" class="pop-modal btn green-sharp btn-xs btn-outline uppercase"
                                            data-href="{{ URL::route('owner.job-postings.hire', ['cancelled', $row->id, $row->post_author]) }}" 
                                            data-toggle="modal"
                                            data-target=".ask-modal" 
                                            data-title="Cancel Application"
                                            data-val="{{ @App\Setting::get_setting('applicant_cancel') }}">Cancel</a>
                                        @endif
           

                                        @if( $row->post_content )
                                        <a href="#" class="pop-modal btn blue-sharp btn-xs btn-outline uppercase"
                                            data-href="{{ URL::route('owner.job-postings.view-letter', $row->id) }}" 
                                            data-toggle="modal"
                                            data-type="ajax"
                                            data-target=".view-modal" 
                                            data-title="<b>{{ $user->fullname }}</b> Cover Letter"
                                            data-body="">View Cover Letter</a>   
                                        @endif 
                                    </div>


                                    </td>
                                    <td>{{ provider_type($user->get_meta($row->post_author, 'provider_type')) }}</td>
                                    <td>
                                        {{ date_formatted($row->updated_at) }}<br>
                                        <small>{{ time_ago($row->updated_at) }}</small>
                                    </td>
                                    <td>
                                        {{ status_ico($row->post_status) }}
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                      
                            @else
                            <h3 class="text-center alert alert-info">No applicants at this moment.</h3>
                            @endif

                        </div>    

                </div>
            </div>


        </div>
    </div>
</div>


<div class="modal fade view-modal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title uppercase">Title goes here ...</h4>
        </div>
        <div class="modal-body">
                 
        </div>

        <div class="modal-footer">
        <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
        <span class="msg"></span>           
        </div>
       
    </div>
  </div>
</div>

<div class="modal fade ask-modal"  tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
            <h4 class="modal-title uppercase">Title goes here ...</h4>
        </div>

        <form method="post" class="form-horizontal form-submit" action="">
        {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="6"></textarea>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
        <button class="btn btn-default btn-primary uppercase" type="submit">Confirm</button> 
        <button class="btn btn-default uppercase" aria-hidden="true" data-dismiss="modal" class="close" type="button">Close</button> 
        </div>

        </form>
      
    </div>
  </div>
</div>




@endsection



@section('top_style')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style>
pre {
    white-space: pre-wrap;
    word-break: normal;
    background: #fff;
    font-family: "Open Sans",sans-serif;
    font-size: 1em;
    line-height: 1.5;   
    border: 0;  
}
table.dataTable.no-footer {
    border-color: #e7ecf1;   
}  
.icon {
    color: rgba(0,0,0,0.4);
    margin: 13px 10px 0;
    font-size: 2.1em;
}
.bg-grey .box {
    display: -webkit-box;    
}
.bg-grey {
    padding: 10px 20px 10px 0;
    background: #f3f3f3!important;
    border: 1px solid #fff;
    margin: 0;  
}
.img-thumbnail { width: 50px; }
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

$(document).on('click', '.pop-modal', function() {
    var url = $(this).data('href'), type = $(this).data('type'), val = $(this).data('val');
    
    $('.modal .modal-body textarea').val(val);      

   if(type == 'ajax') {
        $.get(url, function(res) {
            $('.view-modal .modal-body').html(res); 
        });    
   }
      

});

</script>


@include('partials.delete-modal')
@stop
