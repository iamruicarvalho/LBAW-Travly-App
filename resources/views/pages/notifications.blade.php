@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('home') }}">🏠 Home</a></li>
            <li><a href="{{ route('explore') }}">🔍 Explore</a></li>
            <li><a href="{{ route('notifications') }}">🔔 Notifications</a></li>
            <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
            <li><a href="#">🌎 Wish List</a></li>
            <li><a href="{{ route('groups.showGroups') }}">👥 Groups</a></li>
        </ul>
        <div class="profile-section">
            <!-- Profile information here -->
            <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="notifications">
            @forelse ($notifications as $notification)
                @include('partials.showNotification')
            @empty
                <p>No notifications found.</p>
            @endforelse
        </div>

    <link href="{{ url('css/notifications.css') }}" rel="stylesheet">
@endsection