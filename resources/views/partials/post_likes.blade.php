@extends('layouts.app')

@section('content')
        <div class="post-item">
            <div class="post-content">
                <p>{{ $post->content_ }}</p>
                <p>{{ $post->description_ }}</p>
            </div>
            <div class="post-details">
                <a href="post-likes" class="show-details"> {{ $post->likes_ }} likes</a>
                <a href="post-comments" class="show-details"> {{ $post->comments_ }} comments</a>
                <a> {{ $post->time_ }}</a>
            </div>
        </div>
        <div>
        <h3>Likes:</h3>
        <ul>
            @foreach($likes as $like)
                <li>{{ $like->user->username }}</li>
            @endforeach
        </ul>
        </div>

@endsection