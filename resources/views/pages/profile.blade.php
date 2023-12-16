@extends('layouts.app')

@section('content')

<div class="profile-container">
    <div class="profile-sidebar-header-container">
    {{-- Left Sidebar --}}
    <div class="profile-sidebar-container">
    <div class="left-sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('home') }}">🏠 Home</a></li>
            <li><a href="{{ route('explore') }}">🔍 Explore</a></li>
            <li><a href="#">🔔 Notifications</a></li>
            <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
            <li><a href="#">🌎 Wish List</a></li>
            <li><a href="{{ route('groups') }}">👥 Groups</a></li>
        </ul>
        <div class="profile-section">
            <!-- Profile information here -->
            <a href="{{  route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
        </div>
    </div>
    </div>

        <div class="profile-header">
            <div class="header-container">
                <img src="{{ asset($user->header_picture) }}" alt="Header Picture" class="profile-header-picture">
            </div>
            <div class="profile-container">
                <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
            </div>
            <div>
                @if (auth()->user() == $user)
                    <a href="{{ route('notifications', auth()->id()) }}" class="edit-profile-link">Notifications</a>
                    <a href="{{ route('profile.edit', auth()->id()) }}" class="edit-profile-link">Edit Profile</a>
                @else
                    <a href="#" class="send-friend-request-link">Notifications</a>
                    <a href="#" class="send-friend-request-link">Send friend request</a>
                @endif
            </div>
            <div class="user-info">
                <div>
                    <h3>{{ $user->name_ }}</h3>
                    <p>{{ $user->username }}</p>
                    <p>{{ $user->description_ ? $user->description_ : 'Add description' }}</p>
                    <p>{{ $user->location ? $user->location : 'Add location' }}</p>
                </div>
                <div>
                    <p>{{ $user->followers()->count() }} Followers {{ $user->following()->count() }} Following</p>
                </div>
            </div>
        </div>
        </div>

        <div class="profile-body">
            @if (Auth()->user() == $user)
                <div class="post">
                    <!-- Add user posts or a timeline here -->
                    @if (Auth()->user()->posts()->count() > 0)
                        @foreach(Auth()->user()->posts()->get() as $post)
                            <div class="post-item">
                                <div class="post-content">
                                    <img src="{{ asset('postimage/' . $post->content_) }}">
                                    <p>{{ $post->description_ }}</p>
                                </div>
                                <div class="post-details">
                                    <a href="{{ url('/posts/' . $post->postid . '/likes') }}">{{ $post->likes_ }} likes</a>
                                    <a href="{{ url('/posts/' . $post->postid . '/comments') }}" class="show-details"> Comments</a>
                                    <a> {{ $post->time_ }}</a>
                                    <a onclick="return confirm('Are you sure to delete this?')" href="{{url('my_posts_del', $post->postid)}}" class="btn btn-danger">Delete</a>
                                    <a href="{{url('post_update_page',$post->postid)}}" class="btn btn-primary">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Looks like there are no posts yet 😭</p>
                    @endif
                </div>
            @else
                <p>You don't have access to this user's posts</p>
            @endif    
            
            {{-- Right Sidebar --}}
            <div class="profile-right-sidebars">
                <div class="right-sidebar">
                    <div class="countries-visited">
                        <h3>Countries visited</h3>
                        <p> {{ auth()->user()->countries_visited }}/195 </p>
                    </div>
                </div>
            {{-- Right Sidebar --}}
                <div class="right-sidebar">
                    <div class="Wish list destinations">
                        <h3>Wish list destinations</h3>
                            <ul>
                                <li>Rio de Janeiro, Brasil</li>
                                <li>Paris, França</li>
                                <li>Mikonos, Grécia</li>
                                <!-- Add more as saved -->
                            </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link href="{{ url('css/profile.css') }}" rel="stylesheet">
@endsection
