@forelse ($notifications as $notification)
    <div class="notification-container">
        <h2> {{ $notification->notificationid }}</h2>
        <p> Sent: {{ $notification->time_ }} </p>
        <p> Description: {{ $notification->description_ }} </p>
        <p> Type: {{ $notification->get_type() }} </p>
        <form method="POST" action="{{ route('notifications.remove') }}">
            @csrf
            <input type="hidden" name="notificationid" id="notificationid" value='{{ $notification->notificationid }}'>
            <button type="submit">Remove</button>
        </form>
    </div>
@empty
    <p>No notifications found.</p>
@endforelse