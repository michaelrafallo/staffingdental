<?php 
    $message = new App\Post();
    $inbox = $message->searchMsg()->count(); 
    $unread = $message->searchMsg(['status' => 'unread'])->count();
    $important = $message->searchMsg(['status' => 'important'])->count();
    $archived = $message->searchMsg(['status' => 'archived'])->count();
    
    $auth_group = Auth::User()->group;

?>
<div class="inbox-sidebar">
    <ul class="inbox-nav">
        <li class="{{ actived('', Input::get('status')) }}">
            <a href="{{ URL::route('messages.index', $auth_group) }}"> Inbox

            @if($inbox)
            <span class="badge badge-success">{{ number_format($inbox) }}</span>
            @endif

            </a>
        </li>
        <li class="{{ actived('unread', Input::get('status')) }}">
            <a href="{{ URL::route('messages.index', [$auth_group, 'status' => 'unread']) }}"> Unread 

            @if($unread)
            <span class="badge badge-danger">{{ number_format($unread) }}</span>
            @endif

            </a>
        </li>
        <li class="{{ actived('important', Input::get('status')) }}">
            <a href="{{ URL::route('messages.index', [$auth_group, 'status' => 'important']) }}"> Important 

            @if( $important )
            <span class="badge badge-success">{{ number_format($important) }}</span>
            @endif

            </a>
        </li>
        <li class="divider"></li>
        <li class="{{ actived('archived', Input::get('status')) }}">
            <a href="{{ URL::route('messages.index', [$auth_group, 'status' => 'archived']) }}" class="sbold uppercase"> Archived

            @if($archived)
            <span class="badge badge-success">{{ number_format($archived) }}</span>
            @endif

            </a>
        </li>
    </ul>
</div>