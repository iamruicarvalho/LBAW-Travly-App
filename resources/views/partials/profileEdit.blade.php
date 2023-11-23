@extends('layouts.app')

@section('content')

    {{-- Left Sidebar --}}
    <div class="profile-container">
        <div class="profile-sidebar-header-container">
        <div class="profile-sidebar-container">
            <div class="left-sidebar">
                <ul class="sidebar-menu">
                    <li><a href="{{ route('home') }}">🏠 Home</a></li>
                    <li><a href="#">🔍 Explore</a></li>
                    <li><a href="#">🔔 Notifications</a></li>
                    <li><a href="#">📨 Messages</a></li>
                    <li><a href="#">🌎 Wish List</a></li>
                    <li><a href="#">👥 Groups</a></li>
                    <li><a href="#">➕ More</a></li>
                </ul>
                <div class="profile-section">
                    <a href="{{ route('profile.show') }}">👤 Profile</a>
                </div>
            </div>
        </div>

        {{-- Profile Header --}}
        <div class="profile-header">
            <img src="https://64.media.tumblr.com/bcb1405628a8b4a3c157295ed2b76902/tumblr_inline_p7garrvPza1rzz0uv_500.png" alt="Header Picture" class="profile-header-picture">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Picture" class="profile-picture">

            <div class="profile-editable-fields">
            {{-- Editable fields --}}
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ auth()->user()->username }}" required>

                <label for="description">Description</label>
                <input id="description" placeholder="write a description" name="description" value="{{ auth()->user()->description }}" required>

                <label for="location">Location</label>
                <input id="location" type="text" name="location" value="{{ auth()->user()->location }}" required>

                <div class="profile-save-changes">
                <button type="submit">Save Changes</button>
                </div>
            </form>
            {{-- End Editable fields --}}
            </div>

            <div class="user-info">
                <p>0 following 0 followers</p>
            </div>
        </div>
        </div>

        {{-- Profile Body --}}
        <div class="profile-body">
            <div class="post">
                <p>user posts</p>
            </div>

            {{-- Right Sidebar --}}
            <div class="profile-right-sidebars">
                <div class="right-sidebar">
                    <div class="countries-visited">
                    <h3>Countries visited</h3>
                    <p> {{ auth()->user()->countries_visited }}/195 </p>
                    </div>
                </div>

                <div class="right-sidebar">
                    <div class="Wish list destinations">
                        <h3>Wish list destinations</h3>
                        <ul>
                            <li>Rio de Janeiro, Brasil</li>
                            <li>Paris, França</li>
                            <li>Mikonos, Grécia</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection