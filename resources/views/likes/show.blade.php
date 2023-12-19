<!-- resources/views/likes/show.blade.php -->


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

        <div class="messages-show">
            <div class="header">
                <h1>Likes</h1>
                <div class="new-message-link">
                    <a href="#">❤️</a>
                </div>
            </div>
            <div class="conversations-list">
                @forelse($likers as $liker)
                    <div class="conversation-item">
                        <div class="user-avatar"></div>
                        <div class="conversation-details">
                            <p class="user-name">{{ $liker->user ? $liker->user->name_ : 'Not Found' }}</p>
                            <p class="username">{{ $liker->user ? $liker->user->username : '' }}</p>
                        </div>
                    </div>
                    </a>
                    @empty
                    <li class="no-likes-message">No likes yet.</li>
                @endforelse
            </div>
        </div>

    {{-- Right Sidebar --}}
        <div class="right-sidebar">
            <div class="search-bar">
                {{-- Your search bar HTML goes here --}}
                <input type="text" placeholder="🔍 Search...">
            </div>
            <div class="suggested-groups">
                {{-- Your suggested groups content goes here --}}
                <h3>Suggested Groups</h3>
                <!-- Display suggested groups -->
                <ul>
                    <li>Italia Lovers</li>
                    <li>I heart Japan</li>
                    <li>Budget travel</li>
                    <!-- Add more suggested groups as needed -->
                </ul>
            </div>

            <div class="people-near-you">
                {{-- Your people near you content goes here --}}
                <h3>People Near You</h3>
                <!-- Display people near you -->
                <ul>
                    <li>Bessie Cooper</li>
                    <li>Olivia Silva</li>
                    <li>Joseph Martini</li>
                    <!-- Add more people as needed -->
                </ul>
            </div>

            <div class="trending-hashtags">
                {{-- Your trending hashtags content goes here --}}
                <h3>Trending Hashtags</h3>
                <!-- Display trending hashtags -->
                <ul>
                    <li>#Travel</li>
                    <li>#Adventure</li>
                    <li>#Explore</li>
                    <!-- Add more hashtags as needed -->
                </ul>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css">

    <link href="{{ url('css/likes_list.css') }}" rel="stylesheet">
@endsection
