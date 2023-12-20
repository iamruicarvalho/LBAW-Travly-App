@extends('layouts.app')

@section('content')
<div class="followers-container">
    {{-- Left Sidebar --}}
    <div class="profile-sidebar-container">
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="#">🔍 Explore</a></li>
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="#">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups.showGroups') }}">👥 Groups</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{  route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>
    </div>
    <ul class="followers">
        @forelse($user->followers()->get() as $follower)
            <li><a href="{{ route('profile.show', $follower->id) }}">{{ $follower->username }}</a></li>
        @empty
            @if(Auth()->user() == $user)
                <p class="no-followers">You don't have any followers</p>
            @else
                <p class="no-followers">No followers</p>
            @endif
        @endforelse
    </ul>
</div>
<link href="{{ url('css/followers_following.css') }}" rel="stylesheet">
@endsection