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
                    <a href="{{ route('profile.show', auth()->id()) }}">👤 Profile</a>
                </div>
            </div>
        </div>

        {{-- Profile Header --}}
        <div class="profile-header">
            <img src="https://i.pinimg.com/564x/c6/24/3b/c6243b6f22e618863b06d0e190be214a.jpg" alt="Header Picture" class="profile-header-picture">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Picture" class="profile-picture">

            <div class="profile-editable-fields">
                <script>
                    let authUser = @json($user);
                    let allUsers = @json($allUsers);
                    let otherUsers = allUsers.filter(
                        user => user.id !== authUser.id
                    )
                </script>

                {{-- Editable fields --}}
                <form method="POST" action="{{ route('profile.update', auth()->id()) }}" id="update-profile-form">
                    @csrf
                    <div class="username-input-control">
                        <label for="username">Name</label>
                        <input id="username" type="text" name="username" value="{{ auth()->user()->username }}" required autocomplete="username">
                        <p class="error"></p>
                    </div>

                    <label for="description">Description</label>
                    <input id="description" name="description" value="{{ auth()->user()->description_ }}" required autocomplete="description">

                    <label for="location">Location</label>
                    <input id="location" type="text" name="location" value="{{ auth()->user()->location }}" required autocomplete="location">

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
                <!-- Add user posts or a timeline here -->
                @foreach(Auth()->user()->posts()->get() as $post)
                    <div class="post-item">
                        <div class="post-content">
                            <p>{{ $post->content_ }}</p>
                            <p>{{ $post->description_ }}</p>
                        </div>
                        <div class="post-details">
                            <a href="post.likes" class="show-details"> {{ $post->likes_ }} likes</a>
                            <a href="post.comments" class="show-details"> {{ $post->comments_ }} comments</a>
                            <a> {{ $post->time_ }}</a>
                        </div>
                    </div>
                @endforeach
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
    <link href="{{ url('css/profile.css') }}" rel="stylesheet">
    <script defer src="{{ asset('js/update_profile.js') }}"></script>
@endsection