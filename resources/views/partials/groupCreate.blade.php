@extends('layouts.app')

@section('content')

<div class="group-container">

    {{-- Left Sidebar --}}
    <div class="sidebar-container">
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="{{ route('explore') }}">🔍 Explore</a></li>
                <li><a href="{{ route('notifications') }}">🔔 Notifications</a></li>
                <li><a href="{{ route('messages.showAllConversations') }}">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="{{ route('groups.showGroups') }}">👥 Groups</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{  route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="group-create-container">
            <h2>Create a New Group</h2>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        
            <form method="post" action="{{ route('groups.create') }}">
                @csrf

                <div class="form-group">
                    <label for="name_">Group Name</label>
                    <input type="text" id="name_" name="name_" class="form-control" placeholder="Enter group name" required>
                </div>

                <div class="form-group">
                    <label for="description_">Group Description</label>
                    <textarea id="description_" name="description_" class="form-control" placeholder="Enter group description"></textarea>
                </div>

                <div class="form-group">
                    <a> Selected Members: </a>
                    <ul id="selectedMembersList"></ul>
                    <input type="hidden" name="selectedMembers" id="selectedMembers" value="">
                </div>

                <button type="submit" class="btn btn-primary" id="submitButton">Create Group</button>

            </form>

            <div class="form-group">
                <a>Add members to the group</a>
                <form id="users-search-bar" action="{{ route('users.search') }}" method="GET">
                    @csrf
                    <input type="text" id="search-users" name="query" placeholder="🔍 Search users ..." autocomplete="off">
                </form>
                <ul id="users-list"></ul>
            </div>
        </div>
    </div>

    </div>
    <link href="{{ url('css/group.css') }}" rel="stylesheet">
    <script src="{{ asset('js/createGroup.js') }}" defer></script>
@endsection



