@extends('layouts.app')

@section('content')
<div class="following-container">
    {{-- Left Sidebar --}}
    <div class="profile-sidebar-container">
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
                <a href="{{  route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>
    </div>
    <ul class="following">
        @forelse($user->following()->get() as $following)
            <li><a href="{{ route('profile.show', $following->id) }}">{{ $following->username }}</a></li>
        @empty
            @if(Auth()->user() == $user)
                <p class="no-following">You don't follow anyone</p>
            @else
                <p class="no-following">Doesn't follow anyone</p>
            @endif
        @endforelse
    </ul>
</div>
<link href="{{ url('css/followers_following.css') }}" rel="stylesheet">
@endsection