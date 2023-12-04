@forelse ($notifications as $notification)
    <div class="notification-container">
        <h2> {{ $notification->notificationID }}</h2>
        <p> Sent: {{ $notification->time_ }} </p>
        <p> Description: {{ $notification->description_ }} </p>
    </div>
@empty
    <p>No groups found.</p>
@endforelse