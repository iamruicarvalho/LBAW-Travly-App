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
    
        <div class="followers-container">
        <div class="profile-sidebar-container">
            <div class="left-sidebar">
                <!-- Seu conteúdo atual da barra lateral -->
                <!-- ... -->
            </div>
        </div>

        <div class="followers-content">
            <div class="followers-header">
                <h1>Followers</h1>
            </div>
            <ul class="followers">
                @forelse($user->followers as $follower)
                    <li class="follower-item">
                        <a href="{{ route('profile.show', $follower->id) }}" class="follower-link">
                            <img src="{{ asset($follower->profile_picture) }}" alt="{{ $follower->name }}" class="follower-picture">
                            <div class="follower-info">
                                <span class="follower-name">{{ $follower->name_ }}</span>
                                <span class="follower-username">{{ $follower->username }}</span>
                            </div>
                        </a>
                    </li>
                @empty
                    @if(Auth::user() == $user)
                        <p class="no-followers">You don't have any followers yet.</p>
                    @else
                        <p class="no-followers">This user has no followers.</p>
                    @endif
                @endforelse
            </ul>
        </div>
    </div>
</div>
<link href="{{ url('css/followers_following.css') }}" rel="stylesheet">
@endsection