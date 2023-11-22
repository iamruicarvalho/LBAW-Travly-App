@extends('layouts.app')


@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="#">🏠 Home</a></li>
                <li><a href="#">🔍 Explore</a></li>
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="#">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="#">👥 Groups</a></li>
                <li><a href="#">➕ More</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{ route('profile.show') }}">👤 Profile</a>
                <!-- <a href="{{ route('profile.show', auth()->id()) }}">User Name</a> -->
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Top Section --}}
            <div class="top-section">
                <div class="user-info">
                    <h2>🏠 Home</h2>
                </div>
            </div>
            <form action="{{ url('/post') }}" method="post">
                @csrf
                <div class="upload-post-section">
                    {{-- Seu HTML de upload de post vai aqui --}}
                    <input type="text" name="content" placeholder="Write your post...">
                    <button type="submit" class="post-button">Post</button>
                </div>
            </form>
            <div class="welcome-post">
                <div class="post-header">
                    <span class="user-name">Travly</span>
                </div>
                <div class="post-content">
                    <p>Welcome to Travly! Start exploring and sharing your travel experiences.</p>
                </div>
                <div class="post-actions">
                    <button class="like-button" onclick="toggleLike()"> 
                        <span class="heart-icon">❤️</span>
                        <span class="like-count">0</span>
                    </button>
                    <textarea class="comment-input" placeholder="Add a comment..."></textarea>
                    <button class="comment-button" onclick="addComment()">Comment</button>
                </div>
                <div class="comments-section">
                    <p>John Doe: I love this!</p>
                    <!-- Lista de comentários aqui -->
                    <!-- Cada comentário pode ter um autor e o texto do comentário -->
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
@endsection

