@extends('layouts.app')

@section('content')

<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">My Messages</h1>
<!-- END PAGE TITLE-->

<div class="inbox">
    <div class="row">
        <div class="col-md-2">
        @include('messages.menu')
        </div>
        <div class="col-md-10">
            <div class="inbox-bodyx">

                <div class="inbox-header">
                    <h1 class="pull-left">{{ Input::get('status') ? ucwords(Input::get('status')) : 'Inbox' }}</h1>
                </div>


                <div class="inbox-content">
                <form method="post">
                {!! csrf_field() !!}     
                <input type="hidden" name="action" value="">

                <div class="table-responsive">
                <table class="table table-striped table-advance table-hover">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" class="mail-group-checkbox" id="check_all">
                                    <span></span>
                                </label>

                                @if( Input::get('status') != 'sent' )
                                <div class="btn-group input-actions">
                                    <a class="btn btn-sm blue btn-outline dropdown-toggle sbold" href="javascript:;" data-toggle="dropdown"> Actions
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="javascript:;" data-val="read">
                                                <i class="fa fa-eye"></i> Mark as Read </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-val="not-read">
                                                <i class="fa fa-eye-slash"></i> Mark as Unread </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-val="important">
                                                <i class="fa fa-star"></i> Mark as Important </a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-val="not-important">
                                                <i class="fa fa-star-half"></i> Unmark as Important </a>
                                        </li>

                                        <li>
                                            <a href="javascript:;" data-val="important">
                                                <i class="fa fa-star"></i> Mark as Important </a>
                                        </li>       

                                        <li class="divider"> </li>
                                        <li>
                                            <a href="javascript:;" data-val="archived">
                                                <i class="fa fa-archive"></i> Move to Archive </a>
                                        </li>
                                    </ul>
                                </div>
                                @endif

                            </th>
                            <th class="pagination-control" colspan="3">
                                

                                <span class="pagination-info"> <strong>{{ $total }}</strong> message{{ is_plural($total) }} </span>

                            </th>

                        </tr>
                    </thead>
                    <tbody> 
    
                    @foreach($rows as $row)
                        <?php   

                            $group = $row->user->find($row->post_author)->group;

                            $m = App\Post::where('parent', $row->id)->orderBy('id', 'DESC')->first(); 

                        if( Auth::User()->id ==  $row->post_author) {
                            $url = URL::route('messages.view', [$group, $row->post_title]);
                            $name = name_formatted($row->post_title, 'f l');
                        } else {
                            $url = URL::route('messages.view', [$group, $row->post_author]);
                            $name = name_formatted($row->post_author, 'f l');
                        }


                        $status = 'unread';
                        if( @in_array($user_id, json_decode($row->get_meta($row->id, 'read'))) ) {
                            $status = '';
                        }
                        ?>

                        <tr class="{{ $status }} view-msg">
                            <td class="inbox-small-cells" width="1"> 
                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input name="ids[]" type="checkbox" class="mail-checkbox checkboxes" value="{{ $row->id }}">
                                    <span></span>
                                </label>
                            </td>
                            <td class="inbox-small-cells" width="1">

                                @if( @in_array($user_id, json_decode($row->get_meta($row->id, 'important'))) )
                                <i class="fa fa-star text-warning"></i>
                                @else
                                <i class="fa fa-star"></i>
                                @endif

                            </td>
                            <td class="view-message" width="200"> 

                            <p class="no-margin margin-bottom-10">{!! $name !!}</p>
                            <a href="{{ $url }}" class="uppercase small view">View Message</a>

                            </td>
                            <td class="view-message" width="50%"> 
                                <?php 
                                $m_group = App\User::find($row->post_author)->group; 
                                if( $group == 'provider') {                                    
                                    $group = $m_group;
                                } else {
                                    $group = 'provider';                                   
                                }

                                ?>    
                                <p class="no-margin">{!! str_limit(strip_tags(str_replace('<br>', ' ', decode_message($group, $name, $m->post_content))), 200) !!} </p>                                  
                            </td>
                            <td class="view-message" width="120"><div class="text-right">{{ @time_ago($m->created_at) }}</div></td>
                        </tr>
                        <?php $group= '';?>
                        @endforeach

                        @if( count($rows)==0 )
                        <tfoot>
                            <tr>
                                <td colspan="4"><h3 class="text-center">{{ Input::get('status') ? ucwords(Input::get('status')) : 'Inbox' }} message is empty.</h3></td>
                            </tr>
                        </tfoot>
                        @endif

                    </tbody>
                </table>
                </form>
                </div>
                 
                {{ $rows->links() }}

                </div>


            </div>
        </div>
    </div>
</div>
@endsection



@section('top_style')
<link href="{{ asset('assets/apps/css/inbox.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.view { display: none; }    
tr:hover .view { display: block; }   
.view-msg {
    height: 70px;
} 
</style>
@stop

@section('bottom_style')
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
<script>
$(document).on('click','.input-actions .dropdown-menu a',function() {
    var val = $(this).data('val');
    $('[name=action]').val(val);
    $(this).closest('form').submit();

});

//On Click Check All
$(document).on('click change','input[id="check_all"]',function() {
    
    var checkboxes = $('.checkboxes');

    if ($(this).is(':checked')) {
        checkboxes.prop("checked" , true);
        checkboxes.closest('span').addClass('checked');
    } else {
        checkboxes.prop( "checked" , false );
        checkboxes.closest('span').removeClass('checked');
    }
});


//Children checkboxes
$('.checkboxes').click(function() {
    var a = $(".checkboxes");        
    if(a.length == a.filter(":checked").length){
        $('#check_all').prop("checked" , true);
        $('#check_all').closest('span').addClass('checked');
    } else {
        $('#check_all').prop("checked" , false);
        $('#check_all').closest('span').removeClass('checked');            
    }

});
</script>
@stop
