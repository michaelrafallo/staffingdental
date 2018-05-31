@extends('layouts.app')

@section('content')
<h1 class="page-title"> Job Postings</h1>


@include('admin.job-postings.filter')


<div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th width="1">
                <label class="mt-checkbox mt-checkbox-outline">
                <input id="check_all" type="checkbox" name="enable_upgrade" value="1">
                <span></span>
                </label>
            </th>
            <th>Job Title</th>
            <th>Posted By</th>
            <th>Details</th>
            <th>Applicant</th>
            <th>Status</th>
            <th>Date Posted</th>
        </tr>
    </thead>
    <tbody>

        @foreach($rows as $row)
        <?php 
        $postmeta = get_meta($row->postMetas()->get()); 
        ?>
        <tr>
            <td>
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" class="checkboxes" name="ids[]" value="{{ $row->id }}">
                    <span></span>
                    </label>
            </td>  
            <td>
                {{ $row->post_title }}<br>
                <small class="text-muted">ID: {{ $row->id }}</small>

                <div class="margin-top-5">
                    @if( Input::get('type') == 'trash')
                        <a href="#" class="delete btn btn-xs btn-primary uppercase margin-top-10"
                            data-href="{{ URL::route('admin.job-postings.restore', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Restore"
                            data-body="Are you sure you want to restore ID: <b>#{{ $row->id }}</b>?">Restore</a> 

                        <a href="#" class="delete btn btn-xs btn-default uppercase margin-top-10"
                            data-href="{{ URL::route('admin.job-postings.destroy', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Delete Permanently"
                            data-body="Are you sure you want to delete permanently ID: <b>#{{ $row->id }}</b>?">Delete Permanently</a>
                    @else
                        
                        <a href="{{ URL::route('admin.job-postings.edit', $row->id) }}" class="btn green btn-xs uppercase margin-top-10">Edit</a>

                        @if($row->status != 1)
                        <a href="#" class="delete btn btn-xs btn-default uppercase margin-top-10"
                            data-href="{{ URL::route('admin.job-postings.delete', [$row->id, query_vars()]) }}" 
                            data-toggle="modal"
                            data-target=".delete-modal" 
                            data-title="Confirm Delete"
                            data-body="Are you sure you want to move to trash ID: <b>#{{ $row->id }}</b>?">Move to trash</a> 
                        @else
                        <button class="btn btn-xs btn-default uppercase margin-top-10" disabled>Move to trash</button>
                        @endif    
                    @endif                     
                </div>

            </td>
            <td>
                @if(@$row->post_author)
           
                    <a href="{{ URL::route('admin.users.edit', $row->post_author) }}">{!! name_formatted($row->post_author, 'f l') !!}</a><br>

                @endif
            </td>
            <td>
                <small>                
                @if($postmeta->job_type)
                <i class="fa fa-briefcase text-muted small"></i> 
                {{ job_type($postmeta->job_type) }}<br>
                @endif

                <i class="fa fa-clock-o text-muted small"></i> 
                {{ amount_formatted($postmeta->salary_rate) }} / {{ text_to_code($postmeta->salary_type) }}<br>
                <i class="fa fa-calendar text-muted small"></i> 
                {{ years_of_experience($postmeta->years_of_experience) }}<br>
                </small>
            </td>
            <td>0</td>
            <td>
                {{ status_ico(@$postmeta->job_status) }}
                {{ status_ico($row->post_status) }}
            </td>
            <td>
                {{ date_formatted($row->created_at) }}<br>
                <small>{{ time_ago($row->created_at) }}</small>
            </td>
        </tr>
        @endforeach

        @if(count($rows) == 0)
        <tfoot>
            <tr>
                <td colspan="6" class="text-center">
                    <h3>No record found!</h3>
                </td>
            </tr>
        </tfoot>
        @endif   
 
    </tbody>
</table>
</div>

{{ $rows->links() }}

    </form>

@endsection


@section('top_style')
<style>
.img-thumb {
    min-width: 50px;
    max-width: 50px;
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