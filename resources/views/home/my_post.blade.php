@extends('layouts.app')

@section('my_post')
    <form action="{{ route('user_post') }}" method="POST" enctype="multipart/form-data">
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
                <img src="{{ $data->content_ }}">
            </div>
            <div class="post-actions">
                <button class="like-button" onclick="toggleLike()"> 
                    <span class="heart-icon">❤️</span>
                    <span class="like-count">0</span>
                </button>
                <textarea class="comment-input" placeholder="Add a comment..."></textarea>
                <button class="comment-button" onclick="addComment()">Comment</button>
            </div>
            <a onclick="return confirm('Are you sure to delete this?')" href="{{ route('my_posts_del', $data->id) }}" class="btn btn-danger">Delete</a>
            <a href="{{ route('my_posts_del', $data->id) }}" class="btn btn-primary">Update</a>
            <div class="comments-section">
                <!-- Lista de comentários aqui -->
                <!-- Cada comentário pode ter um autor e o texto do comentário -->
            </div>
        </div>
    @endforeach
@endsection
