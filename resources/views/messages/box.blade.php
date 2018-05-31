@foreach($rows as $row)

<li class="{{ message_position($row->post_author, $user_id) }}">
    <img class="avatar" alt="" src="{{ has_photo( $row->get_usermeta($row->post_author, 'profile_picture') ) }}"/>
    <div class="message">
        <span class="arrow"></span>
        <div class="sender">
        <b>{!! name_formatted($row->post_author, 'f') !!} </b>
        <span class="datetime">
        at {{ time_ago($row->created_at)}} </span>
        </div>
        <div class="msg-body">{!! decode_message($group, $user->firstname, $row->post_content) !!}</div>
    </div>
</li>
@endforeach