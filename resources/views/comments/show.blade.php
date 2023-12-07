
















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
            <div class="welcome-post">
                <div class="post-header">
                    @php
                        $user = App\Models\User::find($post->created_by);
                    @endphp
                    <p class="user-name">{{ $user->name_ }}</p>
                </div>
                <div class="post-content">
                    <p class="post-description">{{ $post->description_ }}</p>
                </div>
                <div class="post-image">
                    <img src="{{$post->content_}}">
                </div>
                <div class="post-actions">
                    <button class="like-button" onclick="toggleLike()"> 
                        <span class="heart-icon">❤️</span>
                        <span class="like-count">0</span>
                    </button>
                    <form action="{{url('user_comment')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-post-section">
                            <textarea name="comment" class="comment-input" placeholder="Add a comment..."></textarea>
                            <input type="hidden" name="postid" value="{{ $post->postid }}">
                            <input type="submit" value="Add Comment" class="btn btn-outline-secondary">
                        </div>
                    </form>
                </div>
                <div class="post-details">
                            <a href="post.likes" class="show-details"> {{ $post->likes_ }} likes</a>
                            <a href="post.comments" class="show-details"> {{ $post->comments_ }} comments</a>

                            <a class="show-details"> {{ $post->time_ }}</a>
                </div>

                <a onclick="return confirm('Are you sure to delete this?')" href="{{url('my_posts_del', $post->postid)}}" class="btn btn-danger">Delete</a>
                <a href="{{url('post_update_page',$post->postid)}}" class="btn btn-primary">Update</a>
                <div class="comments-section">
                    <h3>Comments:</h3>

                    @forelse($comments as $comment)
                        <div class="comment-item">
                            <div class="comment-details">
                                <p class="comment-description">{{ $comment->description_ }}</p>
                                <p class="comment-author">Comment by: {{ $comment->user->name_ }}</p>
                                <p class="comment-time">Posted on: {{ $comment->time_ }}</p>
                                <!-- Adicione mais detalhes do comentário conforme necessário -->
                            </div>
                        </div>
                    @empty
                        <p>No comments yet.</p>
                    @endforelse

                    <!-- Adicione um formulário para adicionar novos comentários -->
                    @auth
                    <form action="{{url('user_comment')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-post-section">
                            <textarea name="comment" class="comment-input" placeholder="Add a comment..."></textarea>
                            <input type="hidden" name="postid" value="{{ $post->postid }}">
                            <input type="submit" value="Add Comment" class="btn btn-outline-secondary">
                        </div>
                    </form>
                    @else
                        <p>Please log in to leave a comment.</p>
                    @endauth
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

