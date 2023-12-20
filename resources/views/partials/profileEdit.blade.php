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
        <form method="POST" action="{{ route('profile.update', auth()->id()) }}" id="update-profile-form" enctype="multipart/form-data">
            @csrf
            <div class="header-container">
                <img src="{{ asset($user->header_picture) }}" alt="Header Picture" class="profile-header-picture">
            </div>
            <div class="profile-container">
                <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
            </div>

            <div class="profile-editable-fields">
                {{-- Editable fields --}}
                    <div class="username-input-control">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" value="{{ auth()->user()->username }}" required autocomplete="username">
                        @if ($errors->has('username'))
                            <span class="error">
                                <!-- {{ $errors->first('username') }} -->
                                This username has already been taken. Choose a different one. 
                            </span>
                        @endif
                    </div>

                    <label for="description">Description</label>
                    <input id="description" name="description" value="{{ auth()->user()->description_ }}" required autocomplete="description">

                    <label for="location">Location</label>
                    <input id="location" type="text" name="location" value="{{ auth()->user()->location }}" required autocomplete="location">

                    <label for="header_picture">Header Picture</label>
                    <input id="header_picture" type="file" name="header_picture" accept="image/*">

                    <label for="profile_picture">Profile Picture</label>
                    <input id="profile_picture" type="file" name="profile_picture" accept="image/*">

                    <div class="profile-save-changes">
                        <button type="submit">Save Changes</button>
                    </div>
                </form>
                {{-- End Editable fields --}}
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
@endsection