<h2>Hi {{ $name }}!</h2>

<h3>Your <a href="{{ URL::route('account.index') }}">{{ $site_name }}</a> account has been updated on {{ date('F d, Y') }}</h3>

<p>Account Details:</p>

@if( is_array($postdata) )
<table border="1" cellpadding="5" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th width="20%">Name</th>
      <th width="40%">From</th>
      <th width="40%">To</th>
    </tr>
  </thead>
  @foreach ($postdata as $key => $value)
  <tr>
    <td>{{ $key }}</td>
    @foreach ($value as $v_k => $v_v)
    <td>{{ $v_v }}</td>
    @endforeach
    </tr>
  @endforeach
</table>
@else
{{ $postdata }}
@endif