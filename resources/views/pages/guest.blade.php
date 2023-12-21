

@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('login') }}">🔐 Login</a></li>
                <li><a href="{{ route('register') }}">📝 Register</a></li>
            </ul>
        </div>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Top Section --}}
            <div class="top-section">
                <div class="user-info">
                    <h2>🏠 Home</h2>
                </div>
            </div>

            @if(session()->has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('message') }}
                </div>
            @endif

            @foreach ($posts as $post)
            <div class="welcome-post">
                <div class="post-header">
                    @php
                        $user = App\Models\User::find($post->created_by);
                    @endphp
                    <p class="user-name">{{ $user->name_ }}</p>
                    <p class="user-name">{{ $user->username }}</p>
                    <p class="show-details"> {{ \Carbon\Carbon::parse($post->time_)->diffForHumans() }}</p>

                </div>
                <div class="post-content">
                    <p class="post-description">{{ $post->description_ }}</p>
                </div>
                <div class="post-image">
                    <img src="{{ asset('postimage/' . $post->content_) }}">
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
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="right-sidebar">
            <form id="users-search-bar" action="{{ route('users.search') }}" method="GET">
                @csrf
                <input type="text" id="search-users" name="query" placeholder="🔍 Search users ..." autocomplete="off">
            </form>
            <ul id="users-list"><!-- users will appear here --></ul>
        </div>
    </div>
    <link href="{{ url('css/home.css') }}" rel="stylesheet">
    <script src="{{ asset('js/searchUsers.js') }}" defer></script>
@endsection





