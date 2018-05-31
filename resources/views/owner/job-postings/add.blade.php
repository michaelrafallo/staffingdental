@extends('layouts.app')



@section('content') 

<div class="row margin-top-20">

<div class="col-md-12">
    
    @include('notification')

    <div class="portlet light bordered">
        <h3>New job posting 
        @if(Input::get('hiring_status'))
            <small class="text-danger uppercase"><b>Urgent</b> Hiring <i class="fa fa-exclamation"></i></small>
        @endif
        </h3> 

        <div class="portlet-body margin-top-30">
        
        <form class="form-horizontal form-submit" role="form" method="post">

            <div class="form-group">
                <label class="col-md-3 control-label">Office / Address</label>
                <div class="col-md-8">
                    {!! Form::select('office', ['' => 'Select Office'] + $offices, Input::old('office'), ["class" => "form-control select2"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('office','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Job Title</label>
                <div class="col-md-8">
                    <input type="text" name="job_title" class="form-control" placeholder="Job Title" value="{{ Input::old('job_title') }}">
                    <!-- START error message -->
                    {!! $errors->first('job_title','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Job Description</label>
                <div class="col-md-8">
                 <textarea class="form-control" name="job_description" rows="5" placeholder="Job Description">{{ Input::old('job_description') }}</textarea>    
                <!-- START error message -->
                {!! $errors->first('job_description','<span class="help-block text-danger">:message</span>') !!}
                <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Type of provider you need</label>
                <div class="col-md-8">
                    {!! Form::select('provider_type', ['' => 'Select provider type'] + provider_type(), Input::old('provider_type', @$provider_type), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('provider_type','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>



            <div class="form-group">
                <label class="col-md-3 control-label">Job Type</label>
                <div class="col-md-8">
                    <?php $job_type = Input::get('hiring_status') ? 'temporary' : ''; ?>
                    {!! Form::select('job_type', ['' => 'Select job type'] + job_type(), Input::old('job_type', $job_type), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('job_type','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group schedule full_time part_time" style="{{  in_array(Input::old('job_type', $job_type), ['part_time', 'full_time']) ? '' : 'display:none;' }}">

                <label class="col-md-3 control-label">Working Hours</label>
                <div class="col-md-8">

                <table class="table table-hover table-striped table-condensed">
                    <?php foreach(get_days() as $day_k => $day_v):
                        $availability = Input::old('working_hours');
                        $adk = @$availability[$day_k];
                        $b_from = $adk ? $adk['from'] : 480;
                        $b_to = $adk ? $adk['to'] : 1020

                        ?>
                    <tr>
                        <td width="150">
                            <h5 class="sbold uppercase"><?php echo $day_v; ?></h5>
                            <input name="working_hours[{{ $day_k }}][day]" type="checkbox" class="make-switch availability" data-size="small" 
                            {{ count($adk) ? 'checked' : '' }}>
                        </td>
                        <td style="vertical-align: inherit;"><i class="fa fa-long-arrow-right"></i></td>
                        <td>
                            <div class="col-md-6 pad-fix">
                                <select class="form-control select2" name="working_hours[{{ $day_k }}][from]" {{ is_array($adk) ? '' : 'disabled="disabled"' }}>
                                <?php foreach(get_times() as $time_k => $time_v): ?>
                                <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $b_from) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 pad-fix mt-xs-10">
                                <select class="form-control select2" name="working_hours[{{ $day_k }}][to]" {{ is_array($adk) ? '' : 'disabled="disabled"' }}>
                                <?php foreach(get_times() as $time_k => $time_v): ?>
                                <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $b_to) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                </div>


            </div>


            <div class="form-group schedule temporary" style="{{  Input::old('job_type', $job_type) == 'temporary' ? '' : 'display:none;' }}">
                <label class="col-md-3 control-label">Date / Time Schedule</label>
                <div class="col-md-8">
                    <div class="sched-repeater">
                        <div data-repeater-list="schedule">
                            @if(Input::old('schedule') ) 
                            @foreach( Input::old('schedule') as $sched)
                            <div data-repeater-item class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                    <div class="col-md-4 pad-fix">
                                        <input type="text" name="date" class="form-control datepicker" value="{{ @$sched['date'] }}">                            
                                    </div>
                                    <div class="col-md-3 pad-fix">
                                        <select class="form-control time-from select2" name="time_start">
                                            <?php $time_start = (@$sched['time_start']) ? $sched['time_start'] : 480; ?>
                                            <?php foreach(get_times() as $time_k => $time_v): ?>
                                            <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $time_start) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 pad-fix mt-xs-10">
                                        <select class="form-control time-to select2" name="time_end">
                                            <?php $time_end = (@$sched['time_end']) ? $sched['time_end'] : 1020; ?>
                                            <?php foreach(get_times() as $time_k => $time_v): ?>
                                            <option value="<?php echo $time_k; ?>" <?php echo ($time_k == $time_end) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:;" data-repeater-delete class="btn btn-danger btn-sm mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div data-repeater-item class="mt-repeater-item">
                                <div class="row mt-repeater-row">
                                    <div class="col-md-4 pad-fix">
                                        <input type="text" name="date" class="form-control datepicker" value="{{ date('Y-m-d') }}">                            
                                    </div>
                                    <div class="col-md-3 pad-fix">
                                        <select class="form-control time-from select2" name="time_start">
                                            <?php foreach(get_times() as $time_k => $time_v): ?>
                                            <option value="<?php echo $time_k; ?>" <?php echo ($time_k == 480) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 pad-fix mt-xs-10">
                                        <select class="form-control time-to select2" name="time_end">
                                            <?php foreach(get_times() as $time_k => $time_v): ?>
                                            <option value="<?php echo $time_k; ?>" <?php echo ($time_k == 1020) ? 'selected' : ''; ?>><?php echo $time_v; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="javascript:;" data-repeater-delete class="btn btn-danger btn-sm mt-repeater-delete">
                                        <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif                            
                        </div>
                        <a href="javascript:;" data-repeater-create class="mt-repeater-add">
                        <i class="fa fa-plus"></i> Add Date / Time</a>
                    </div>
                </div>
            </div>

        
            <div class="form-group">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-5">
                    <h5>Salary Type</h5>

                    {!! Form::select('salary_type', ['' => 'Select salary type'] + salary_type(), Input::old('salary_type'), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('salary_type','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>

                <div class="col-md-3">
                    <h5>Salary Rate</h5>
                    <div class="input-group">
                        <input type="text" name="salary_rate" class="form-control numeric" placeholder="Salary Rate" value="{{ Input::old('salary_rate') }}">
                        <span class="input-group-addon salary-prefix">{{ salary_prefix(Input::old('salary_rate', 'daily')) }}</span>
                    </div>

                    <!-- START error message -->
                    {!! $errors->first('salary_rate','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>




            <div class="form-group">
                <label class="col-md-3 control-label">Required years of experience</label>
                <div class="col-md-8">
                    {!! Form::select('years_of_experience', ['' => 'Select years of experience'] + years_of_experience(), Input::old('years_of_experience', @$years_of_experience), ["class" => "form-control"]) !!}
                    <!-- START error message -->
                    {!! $errors->first('years_of_experience','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Number of available position</label>
                <div class="col-md-8">
                    <input type="text" name="number_of_position" class="form-control numeric" placeholder="Number of available position" value="{{ Input::old('number_of_position', 1) }}">
                    <!-- START error message -->
                    {!! $errors->first('number_of_position','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Practice Type</label>
                <div class="col-md-8">
                    <div class="row">

                        <?php 
                        $practice_type = Input::old('practice_type', json_decode($info->practice_type));

                        foreach(practice_types() as $practice_type_k => $practice_type_v): ?>
                        <?php 
                            $checked = (isset($practice_type)) ? (in_array($practice_type_k, $practice_type) ? 'checked' : '') : ''; 
                        ?>
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" name="practice_type[]" value="{{ $practice_type_k }}" 
                            {{ $checked }}> {{ $practice_type_v }}
                            <span></span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- START error message -->
                    {!! $errors->first('practice_type','<span class="help-block text-danger">:message</span>') !!}
                    <!-- END error message -->
                </div>
            </div>




            <div class="form-group margin-top-40">
                <div class="col-md-offset-3 col-md-8">

                    {!! csrf_field() !!}
       
                    <button type="submit" class="btn btn-primary uppercase">Create Job Posting</button>


                    @if(Input::get('hiring_status'))
                        <input type="hidden" name="hiring_status" value="urgent">
                    @endif
                    
                    @if( $info->get_meta($info->id, 'package_amountx') )
                    <a href="#owner-trial-period" data-toggle="modal" class="btn btn-primary uppercase">Create Job Posting</a>
                    @endif

                    <a href="{{ URL::route('owner.job-postings.index') }}" class="btn uppercase pull-right"><i class="fa fa-long-arrow-left"></i> All Jobs</a> 
                </div>
            </div>

        </form>
        
        </div>
    </div>
</div>
</div>





@stop




@section('top_style')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

<style>
.sched-disabled .mt-repeater-add {
    display: none;
}

.sched-repeater .mt-repeater-item {
    border-bottom: 0; 
    padding-bottom: 0;
    margin-bottom: 15px;
}    
.sched-disabled .mt-repeater-row .mt-repeater-delete,
.mt-repeater-item:first-child .mt-repeater-row .mt-repeater-delete {
    display: none;
}
.hiring-status { margin-left: 10px; }   
.pad-fix { padding: 0 0px 0 15px; }
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-repeater/jquery.repeater.js') }}" type="text/javascript"></script>
@stop

@section('bottom_script')
<script>
$('.availability').on('switchChange.bootstrapSwitch', function (event, state) {
    $(this).closest('tr').find('.sched-repeater').addClass('sched-disabled');
    $(this).closest('tr').find('select').attr('disabled', 'disabled');

    if(state == true) {
        $(this).closest('tr').find('.sched-repeater').removeClass('sched-disabled');
        $(this).closest('tr').find('select').removeAttr('disabled');
    }  
}); 


$(document).on('change', '[name=job_type]', function(){ 
   $('.schedule').hide();
   $('.'+$(this).val()).show();
});

$(document).on('change', '[name=salary_type]', function(){ 
    val = '%';
    if( $(this).val() == 'daily' || $(this).val() == 'hourly' ) {
        val = 'USD';
    }

    $('.salary-prefix').html(val);
});

function inc_date(date_str){
  var parts = date_str.split("-");
  var dt = new Date(
    parseInt(parts[0], 10),      // year
    parseInt(parts[1], 10) - 1,  // month (starts with 0)
    parseInt(parts[2], 10)       // date
  );
  dt.setDate(dt.getDate() + 1);
  parts[0] = "" + dt.getFullYear();
  parts[1] = "" + (dt.getMonth() + 1);
  if (parts[1].length < 2) {
    parts[1] = "0" + parts[1];
  }
  parts[2] = "" + dt.getDate();
  if (parts[2].length < 2) {
    parts[2] = "0" + parts[2];
  }
  return parts.join("-");
}

sched_repeater =  function() {
    $(".sched-repeater").each(function() {
        $(this).repeater({
            show: function() {
                app_init();


                var index = $(this).closest('.sched-repeater').find('.mt-repeater-item:last-child').index();
                var val   = $(this).closest('.sched-repeater').find('.mt-repeater-item:nth-child('+index+') .datepicker').val();

                $(this).closest('.sched-repeater').find('.mt-repeater-item:last .datepicker').val( inc_date(val) ); 


                $(this).closest('.sched-repeater').find('.mt-repeater-item:last .time-from').val(480).trigger('change'); 
                $(this).closest('.sched-repeater').find('.mt-repeater-item:last .time-to').val(1020).trigger('change');

                $(this).show('fast');

            },
            hide: function(e) {
                $(this).hide('fast', function(){
                    $(this).remove();
                });
            },
            ready: function(e) {
            }
        })
    });
}

sched_repeater();

</script>
@stop




