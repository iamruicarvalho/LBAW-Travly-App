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
        <img src="https://i.pinimg.com/564x/c6/24/3b/c6243b6f22e618863b06d0e190be214a.jpg" alt="Header Picture" class="profile-header-picture">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Picture" class="profile-picture">
            <a href="{{ route('profile.edit', auth()->id()) }}" class="edit-profile-link">Edit Profile</a>
            <div class="user-info">
                <div>
                    <h3>{{ auth()->user()->username }}</h3>
                    <p>{{ auth()->user()->description_ }}</p>
                    <p>{{ auth()->user()->location }}</p>
                </div>
                <div>
                    <p>0 Followers 0 Following</p>
                </div>
            </div>
        </div>
        </div>

        <div class="profile-body">
            <div class="post">
                <!-- Add user posts or a timeline here -->
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
            </div>
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


