@extends('layouts.app')

@section('content')

<h1 class="page-title">Offices</h1>

<div class="table-responsive">
<table class="table table-striped table-hover">  
   <thead>
      <tr>
         <th>Offices</th>
         <th>Employer</th>
         <th>Amount</th>
         <th>Date Requested</th>
         <th>Status</th>
      </tr>
   </thead>
   <tbody>
      @foreach($requests as $request)
      <tr>
        <td>
        <a href="{{ URL::route('admin.offices.view', $request->id) }}">
          <h5><b>{{ $office_count = count( json_decode($request->post_content) ) }}</b> Dental Office{{ is_plural($office_count) }}</h5>
        </a>
        </td>
        <td>
            @if(@$request->post_author)
       
                <a href="{{ URL::route('admin.users.edit', $request->post_author) }}">{!! name_formatted($request->post_author, 'f l') !!}</a><br>

            @endif
        </td>
        <td>
          @if($request->post_name)
          {{ number_format($request->post_name, 2) }}
          @endif
        </td>
         <td>
          {{ date_formatted($request->created_at) }}
          <p class="no-margin text-muted">{{ time_ago($request->created_at) }}</p>
          </td>
         <td>{{ status_ico($request->post_status) }}</td>
      </tr>
      @endforeach
   </tbody>
</table>

@if( ! count($requests) )
<h4 class="text-center">No {{ Input::get('status') }} office address found at the moment!</h4>
@endif

</div>

{{ $requests->links() }}

@endsection



@section('top_style')
<style>
.form-actions {
    bottom: 0;
    position: fixed;
    z-index: 9999;
    background: #fff;
    width: 100%;
    padding: 15px 0 15px 20px;
    margin: 0 0 0 -20px;
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
$(document).on('click', '.mt-repeater-add', function() {
    $('html, body').animate({
        scrollTop: $('.page-footer').offset().top - 50
    }, 1000);  
});
</script>
@stop


