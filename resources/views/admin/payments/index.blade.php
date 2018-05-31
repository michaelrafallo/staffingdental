@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="page-title"> Payments</h1>
    </div>
</div>

<div class="table-responsive">
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Reference No.</th>
            <th>Details</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <?php $postmeta = get_meta($row->postMetas()->get()); ?>
        <tr>
            <td><a href="{{ URL::route('admin.users.edit', $row->post_author) }}">{{ $row->user->find($row->post_author)->fullname }}</a></td>
            <td>{{ @$postmeta->reference_no }}</td>
            <td>{{ $row->post_title }}</td>
            <td>{{ amount_formatted(@$postmeta->amount) }}</td>
            <td>
                {{ date_formatted($row->created_at) }}<br>
                <small>{{ time_ago($row->created_at) }}</small>
            
            </td>
        </tr>
        @endforeach
    </tbody>
    @if(count($rows) == 0)
    <tfoot>
        <tr>
            <td colspan="5" class="text-center">
                <h3>You dont have any payments at the moment!</h3>
            </td>
        </tr>
    </tfoot>
    @endif   
</table>


</div>

{{ $rows->links() }}

@endsection



@section('top_style')

@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 
@stop
