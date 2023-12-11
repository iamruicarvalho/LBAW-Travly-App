@extends('layouts.app')

@section('content')

<div class="group-container">

    {{-- Left Sidebar --}}
    <div class="profile-sidebar-container">
    <div class="left-sidebar">
        <ul class="sidebar-menu">
            <li><a href="{{ route('home') }}">🏠 Home</a></li>
            <li><a href="#">🔍 Explore</a></li>
            <li><a href="#">🔔 Notifications</a></li>
            <li><a href="#">📨 Messages</a></li>
            <li><a href="#">🌎 Wish List</a></li>
            <li><a href="{{ route('groups.showGroups') }}">👥 Groups</a></li>
        </ul>
        <div class="profile-section">
            <!-- Profile information here -->
            <a href="{{  route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
        </div>
    </div>
    </div>

    <div class="Group-container">
    <h2> {{ $group->name_ }}</h2>
    
            <p> Description: {{ $group->description_ }}</p>
            <a href="{{  route('group.details', $group->groupid) }}" class="view-details">View group details</a>
    </div>

    <form action="{{url('user_post')}}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="upload-post-section">
                <textarea name="description" placeholder="Write your post..."></textarea>
                <div class="upload-btn-wrapper">
                    <button class="btn-upload">📸</button>
                    <input type="file" name="image" accept="image/*">
                </div>
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
                    <p class="user-name">{{ $user->username }}</p>
                    <p class="show-details"> {{ $data->time_ }}</p>

                </div>
                <div class="post-content">
                    <p class="post-description">{{ $data->description_ }}</p>
                </div>
                <div class="post-image">
                    <img src="{{ asset('postimage/' . $data->content_) }}">
                </div>
                <div class="post-details">
                            <a href="{{ url('/posts/' . $data->postid . '/likes') }}" class="show-details"> {{ $data->likes_ }} likes</a>
                            <a href="{{ url('/posts/' . $data->postid . '/comments') }}" class="show-details"> Comments</a>
                            <a class="show-details"> {{ $data->time_ }}</a>
                </div>

                <a onclick="return confirm('Are you sure to delete this?')" href="{{url('my_posts_del', $data->postid)}}" class="btn btn-danger">Delete</a>
                <a href="{{url('post_update_page',$data->postid)}}" class="btn btn-primary">Edit</a>
            </div>

    @endforeach

    <div class="welcome-post">
        <div class="post-header">
            <span class="user-name">Travly</span>
        </div>
        <div class="post-content">
            <p>Welcome to Travly! Start exploring and sharing your travel experiences.</p>
        </div>
    </div>
</div>
    <link href="{{ url('css/group.css') }}" rel="stylesheet">
@endsection


