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
    
            {{-- Main Content --}}
            <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <a href="{{ url('/') }}" class="btn btn-outline-primary mb-3">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>

                    <h1 class="mb-4">Likes for Post</h1>

                    <p><strong>Post Content:</strong> {{ $post->content_ }}</p>

                    <h2>Liked by:</h2>
                    <ul class="list-group">
                        @forelse($likers as $liker)
                            <li class="list-group-item">
                                <strong>User Name:</strong> {{ $liker->user ? $liker->user->name_ : 'Not Found' }}
                            </li>
                        @empty
                            <li class="list-group-item">No likes yet.</li>
                        @endforelse
                    </ul>
                </div>
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
