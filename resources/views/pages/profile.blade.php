@extends('layouts.app')

@section('content')

<div class="profile-container">
    <div class="profile-sidebar-header-container">
        {{-- Left Sidebar --}}
        @guest
            <div class="left-sidebar">
                <ul class="sidebar-menu">
                    <li><a href="{{ route('login') }}">🔐 Login</a></li>
                    <li><a href="{{ route('register') }}">📝 Register</a></li>
                </ul>
            </div>
        @endguest
        @auth
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
                        <a href="{{ route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
                    </div>
                </div>
            </div>
        @endauth


        <div class="profile-header">
            <div class="header-container">
                <img src="{{ asset($user->header_picture) }}" alt="Header Picture" class="profile-header-picture">
            </div>
            <div class="profile-container">
                <img src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="profile-picture">
            </div>
            <div>
                @if (auth()->user() == $user)
                    <a href="{{ route('profile.edit', auth()->id()) }}" class="edit-profile-link">Edit Profile</a>
                @else
                    <!-- add account privacy verification -->
                    @auth
                    <a href="#" class="send-friend-request-link">Notifications</a>
                    <a href="#" class="send-friend-request-link">Send friend request</a>
                    @endauth
                    @guest
                        <p style="color: #D4E4F2;">.</p>
                        <p style="color: #D4E4F2;">.</p>
                    @endguest
                @endif
            </div>
            <div class="user-info">
                <div>
                    <h3>{{ $user->username }}</h3>
                    <p>{{ $user->name_ }}</p>
                    <p>{{ $user->description_ ? $user->description_ : 'Add description' }}</p>
                    <p>{{ $user->location ? $user->location : 'Add location' }}</p>
                </div>
                <!-- Need to see if they are friends. Not implemented yet. Add this when done (    && (weAreFriends || !($user->private_)    )-->
                <div class="user-links">
                    @if (Auth()->user() == $user || (Auth()->user() != $user && !($user->private_)))
                        <div class="followers-following-link">
                            <a href="{{ route('followers', $user->id) }}" class="followers-link">{{ $user->followers()->count() }} Followers</a>
                            <a href="{{ route('following', $user->id) }}" class="following-link">{{ $user->following()->count() }} Following</a>
                        </div>
                    @else
                        <div class="followers-following-link">
                            <p>{{ $user->followers()->count() }} Followers {{ $user->following()->count() }} Following</p>
                        </div>
                    @endif
                    @if (Auth()->user() == $user)
                        <div>
                            <a href="{{ route('profile.delete', $user->id) }}"  class="delete-account-link">Delete Account</a>
                        </div> 
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="profile-body">
        <!-- possibility of seeing other users posts: -->
        <!-- 1. I am seeing my personal profile -->
        <!-- 2. I am visiting a friend's profile -->
        <!-- 3. I am visiting a user that has a public account -->
        <!-- Need to see if they are friends. Not implemented yet. Add this when done (    && (weAreFriends || !($user->private_)    )-->
        @if (Auth()->user() == $user || (Auth()->user() != $user && !($user->private_)))
            <div class="post">
                <!-- Add user posts or a timeline here -->
                @if ($user->posts()->count() > 0)
                    @foreach($user->posts()->get() as $post)
                        <div class="post-item">
                            <div class="post-content">
                                <img src="{{ asset('postimage/' . $post->content_) }}">
                                <p>{{ $post->description_ }}</p>
                            </div>
                            <div class="post-details">
                                <a href="{{ url('/posts/' . $post->postid . '/likes') }}">{{ $post->likes_ }} likes</a>
                                <a href="{{ url('/posts/' . $post->postid . '/comments') }}" class="show-details">{{ $post->comments_ }} Comments</a>
                                <p> {{ \Carbon\Carbon::parse($post->time_)->diffForHumans() }}</p>
                                <!-- If I'm on my personal profile page -->
                                @if (Auth()->user() == $user)  
                                    <a onclick="return confirm('Are you sure to delete this?')" href="{{url('my_posts_del', $post->postid)}}" class="btn btn-danger">Delete</a>
                                    <a href="{{url('post_update_page',$post->postid)}}" class="btn btn-primary">Edit</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>There are no posts yet 😭</p>
                @endif
            </div>            
        <!-- Private account -->
        @else
            <p>This is a private account. You don't have access to this user's posts.</p>
        @endif    
        
        {{-- Right Sidebar --}}
        <div class="profile-right-sidebars">
            <div class="right-sidebar">
                <div class="countries-visited">
                    <h3>Countries visited</h3>
                    <p> {{ $user->countries_visited }}/195 </p>
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
