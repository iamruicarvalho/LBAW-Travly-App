@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Posts related to {{ $city }}</h2>

        @forelse ($post as $post)
        <div class="welcome-post">
            <div class="post-header">
                @php
                    $user = App\Models\User::find($post->created_by);
                @endphp
                <p class="user-name">{{ $user->name_ }}</p>
                <p class="user-name">{{ $user->username }}</p>
                <p class="show-details"> {{ $post->time_ }}</p>

            </div>
            <div class="post-content">
                <p class="post-description">{{ $post->description_ }}</p>
            </div>
            <div class="post-image">
                <img src="{{ asset('postimage/' . $post->content_) }}">
            </div>
            <div class="post-details">
                        <a href="{{ url('/posts/' . $post->postid . '/likes') }}" class="show-details"> {{ $post->likes_ }} likes</a>
                        <a href="{{ url('/posts/' . $post->postid . '/comments') }}" class="show-details"> Comments</a>
                        <a class="show-details"> {{ $post->time_ }}</a>
            </div>

            <a onclick="return confirm('Are you sure to delete this?')" href="{{url('my_posts_del', $post->postid)}}" class="btn btn-danger">Delete</a>
            <a href="{{url('post_update_page',$post->postid)}}" class="btn btn-primary">Edit</a>
        </div>
        @empty
            <p>No posts found for {{ $city }}</p>
        @endforelse
    </div>
    <link href="{{ url('css/by_hashtag.css') }}" rel="stylesheet">
@endsection
