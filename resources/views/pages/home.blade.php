@extends('layouts.app')


@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="#">🔍 Explore</a></li>
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups') }}">👥 Groups</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
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

        <form action="{{url('user_post')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-post-section">
                    {{-- Seu HTML de upload de post vai aqui --}}
                    <textarea name="description" placeholder="Write your post..."></textarea>
                    <input type="file" name="image">
                    <input type="submit" value="Add Post" class="btn btn-outline-secondary">
                </div>
            </form>

            @if(session()->has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('message') }}
                </div>
            @endif

            @foreach($data as $data)

            <div class="welcome-post">
                <div class="post-header">
                    @php
                        $user = App\Models\User::find($data->created_by);
                    @endphp
                    <p class="user-name">{{ $user->name_ }}</p>
                </div>
                <div class="post-content">
                    <p class="post-description">{{ $data->description_ }}</p>
                </div>
                <div class="post-image">
                    <img src="{{ asset('postimage/' . $data->content_) }}">
                </div>
                <a href="{{ url('/posts/' . $data->postid . '/likes') }}">See Likes</a>
                <div class="post-actions">
                    <button class="like-button" onclick="toggleLike()"> 
                        <span class="heart-icon">❤️</span>
                        <span class="like-count">0</span>
                    </button>
                    <form action="{{url('user_comment')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-post-section">
                            <textarea name="comment" class="comment-input" placeholder="Add a comment..."></textarea>
                            <input type="hidden" name="postid" value="{{ $data->postid }}">
                            <input type="submit" value="Add Comment" class="btn btn-outline-secondary">
                        </div>
                    </form>
                </div>
                <div class="post-details">
                            <a href="post.likes" class="show-details"> {{ $data->likes_ }} likes</a>
                            <a href="post.comments" class="show-details"> {{ $data->comments_ }} comments</a>
                            <div class="post-actions">
                                <a href="{{ url('/posts/' . $data->postid . '/comments') }}" class="show-details">Show Comments</a>
                            </div>

                            <a class="show-details"> {{ $data->time_ }}</a>
                </div>

                <a onclick="return confirm('Are you sure to delete this?')" href="{{url('my_posts_del', $data->postid)}}" class="btn btn-danger">Delete</a>
                <a href="{{url('post_update_page',$data->postid)}}" class="btn btn-primary">Update</a>
                <div class="comments-section">
                    <!-- Lista de comentários aqui -->
                    <!-- Cada comentário pode ter um autor e o texto do comentário -->
                </div>
            </div>

            @endforeach

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
    <link href="{{ url('css/home.css') }}" rel="stylesheet">
@endsection

