<ul>
    @foreach($notifications as $notification)
        <li>
            <strong>{{ $notification->data['song_name'] }}</strong>:
            {{ $notification->data['message'] }}
            <small>{{ $notification->created_at->diffForHumans() }}</small>
        </li>
    @endforeach
</ul>