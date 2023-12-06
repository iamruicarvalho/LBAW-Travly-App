@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="#">🔍 Explore</a></li>
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="#">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups') }}">👥 Groups</a></li>
            </ul>
        <div class="profile-section">
            <!-- Profile information here -->
            <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="col justify-content-center">
        <div class="list-group">
            @forelse ($notifications as $notification)
                @include('partials.showNotification')
            @empty
                <p>No notifications found.</p>
            @endforelse
        </div>
    </div>

    <link href="{{ url('css/home.css') }}" rel="stylesheet">
@endsection