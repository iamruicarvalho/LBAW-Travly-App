@extends('layouts.app')

@section('content')

    {{-- Left Sidebar --}}
    <div class="profile-container">
        <div class="profile-sidebar-container">
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
                    <a href="{{ route('profile.show') }}">👤 Profile</a>
                </div>
            </div>
        </div>

        {{-- Profile Header --}}
        <div class="profile-header">
            <img src="https://64.media.tumblr.com/bcb1405628a8b4a3c157295ed2b76902/tumblr_inline_p7garrvPza1rzz0uv_500.png" alt="Header Picture" class="profile-header-picture">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Picture" class="profile-picture">
            <a href="{{ route('profile.edit') }}" class="edit-profile-link">Edit Profile</a>

            {{-- Editable fields --}}
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>

                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>

                <button type="submit">Save Changes</button>
            </form>
            {{-- End Editable fields --}}

            <div class="user-info">
                    <p>number following number followers</p>
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
                        <p>3/195</p>
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