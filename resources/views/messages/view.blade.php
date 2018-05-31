@extends('layouts.app')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">My Messages</h1>
<!-- END PAGE TITLE-->
<div class="inbox">
    <div class="row">
        <div class="col-md-3">
        @include('messages.menu')
        </div>


        <div class="col-md-9">

        @include('notification')

            <!-- BEGIN PORTLET-->
            <div class="portlet">
                <div class="portlet-title line select-disabled">

                    @if($user)
                    <img class="img-circle pull-left" src="{{ has_photo($user->get_meta($uid, 'profile_picture')) }}"/>

                    <?php $url = ($group == 'owner') ? 'provider.job-postings.employer' : 'owner.dentalpro.profile'; ?>
                    <strong class="uppercase"><a href="{{ URL::route($url, $uid) }}">{!! name_formatted($user->id, 'f l') !!}</a></strong><br>
                    @endif

                    @if($group == 'provider')
                    {{ provider_type(App\UserMeta::get_meta($uid, 'provider_type')) }}<br>         
                    <small class="uppercase text-muted">{!! profile_status($user->get_meta($uid, 'availability'), true) !!}</small>
                    @else
                    Dental Owner
                    @endif

                    <div class="tools">
                        <a href="" class="fullscreen">
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="scroller" id="msg" style="height: 352px;" data-always-visible="1" data-rail-visible1="1">
                        <ul class="chats">
                        @include('messages.box')
                        </ul>
                    </div>

                    <form method="post">
                    {{ csrf_field() }}       

                    <div class="chat-form">
                        <div class="input-cont">
                            <input class="form-control" type="text" name="message" placeholder="Type a message here..."/>
                        </div>
                        <div class="btn-cont">
                            <span class="arrow">
                            </span>
                            <button type="submit" class="btn blue icn-only">
                            <i class="fa fa-check icon-white"></i>
                            </button>
                        </div>
                    </div>

                    </form>


                    <span id="message"></span>

                </div>
            </div>
            <!-- END PORTLET-->
        </div>


    </div>
</div>

<audio id="play-success">
  <source src="{{ asset('uploads/success.mp3') }}" type="audio/mpeg">
</audio>

<audio id="play-error">
  <source src="{{ asset('uploads/error.mp3') }}" type="audio/mpeg">
</audio>

@endsection



@section('top_style')
<link href="{{ asset('assets/apps/css/inbox.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('bottom_style')
<style>
.inbox .img-circle {
    width: 60px;
    height: 60px;
    margin-right: 10px;
}
.msg-body {
    background: #fff;
    font-family: inherit;
    font-size: 1.2em;
    border: 1px dashed #e6e6e6;
    margin-top: 10px;
    line-height: 1.5;
    padding: 15px;
}    
.chats li.out .message {
    text-align: left;
}
.chats li.out .sender {
    text-align: right;
}

.chats {
    padding: 20px;
    background: #e9eff3;
}
</style>
@stop

@section('bottom_plugin_script') 

@stop

@section('bottom_script')
<script>

var box_url = "{{ URL::route('messages.sent', [@$user->group, @$user->id]) }}";

$('#msg').slimScroll({
    color: '#3598dc',
    start: 'bottom'
});
$('form').on('submit', function(e) {
    e.preventDefault();
    
    @if( $user->get_meta($user_id, 'package_amountx') == 0 && $user_group == 'ownerx')
        $('#owner-trial-periodx').modal('show');
    @else
    @endif

        var url = $(this).closest('form').attr('action');
            formData = $(this).closest('form');

    $('.text-danger').html('');

    $.ajax({
        url: box_url, // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method 
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(response)   // A function to be called if request succeeds
        {
            var IS_JSON = true;
            try {
                var data = JSON.parse(response);
            } catch(err){
                IS_JSON = false;
            } 

            if( IS_JSON ) {
                $.each(data.details, function(key, val) {
                    $('#'+key).html('<span class="text-danger help-inline sbold">'+val+'</div>');
                });
                $('#play-error')[0].play();
            } else {
                $('.chats').html(response); 
                $('.scroller').animate({
                scrollTop: $('.scroller').get(0).scrollHeight}, 2000);
                $('[name="message"]').val('');
                $('#play-success')[0].play();
            }

        }
    });


}); 

// $('.slimScrollBar').position().top;

/*setInterval(function(){ 
    $.get(box_url, function(response) {
        $('.chats').html(response); 
       // $('.scroller').animate({
       // scrollTop: $('.scroller').get(0).scrollHeight}, 2000);
    });
}, 10000);*/


</script>
@stop
