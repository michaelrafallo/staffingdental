@extends('layouts.app')

@section('content')

<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Favorite providers <small>{{ count($rows) }} provider{{ is_plural(count($rows)) }}</small></h1>
<!-- END PAGE TITLE-->

<div class="row">
    <div class="col-md-4">
    <form action="" method="get">
        <div class="input-group">
            {{ Form::select('provider_type', ['' => 'All'] + provider_type(), Input::get('provider_type'), ['class' => 'form-control']) }}

            <span class="input-group-btn">
                <button class="btn btn-success" type="submit">Filter</button>
            </span>
        </div>          
    </form>
    </div>  
</div>

<div class="row select-disabled">

    @foreach($rows as $row)

    <div class="col-md-2 col-sm-3 margin-top-20 text-center">
        <a href="{{ URL::route('owner.dentalpro.profile', $row->post_title) }}">
            <img src="{{ has_photo($usermeta->get_meta($row->post_title, 'profile_picture')) }}" class="img-thumbnail img-fav">
        </a>

            <h5 class="no-margin uppercase margin-top-10"> 
            {!! profile_status($usermeta->get_meta($row->post_title, 'availability')) !!}

            <a href="{{ URL::route('owner.dentalpro.profile', $row->post_title) }}">
            {!! name_formatted($row->post_title, 'f l') !!}   
            </a> </h5>
            {{ provider_type(App\Usermeta::get_meta($row->post_title, 'provider_type')) }}



    </div>
    @endforeach

</div>

    @if(count($rows)==0)
    <h3> 
        @if( Input::get('provider_type') )
        No {{ provider_type(Input::get('provider_type')) }} found!
        @else
        Search your favorite provider <a href="{{ URL::route('owner.dentalpro.index') }}">Click Here!</a>
        @endif
    </h3>
    @endif

@endsection



@section('top_style')
<style>
.img-fav {
    height: 150px;
}    
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')

@stop
