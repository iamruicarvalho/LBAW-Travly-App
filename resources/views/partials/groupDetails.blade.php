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
    <div class="Group-container">
    <h2> {{ $group->name_ }}</h2>

        @if($group->owners->contains(auth()->user()))
            <form method="post" action="{{ route('group.editName', ['groupid' => $group->groupid]) }}">
                @csrf
                <input type="text" id="newName" name="name_" placeholder="New group name" required>
                <button type="submit">Edit group name</button>
            </form>
        @endif

        <p> Description: {{ $group->description_ }}</p>

        @if($group->owners->contains(auth()->user()))
            <form method="post" action="{{ route('group.editDescription', ['groupid' => $group->groupid]) }}">
                @csrf
                <input type="text" id="newDescription" name="description_" placeholder="New group description" required>
                <button type="submit">Edit group description</button>
            </form>
        @endif

            <h3>Members:</h3>
            @if ($group->users->isEmpty())
                <p>No users found for this group.</p>
            @else
                <ul id="users">
                    @foreach ($group->users as $user)
                        <li id="user">{{ $user->username }}</li>
                        @if($group->owners->contains(auth()->user()))
                            @if(!$group->owners->contains($user))
                                <a href="{{ route('group.removeuser', ['groupid' => $group->groupid, 'userid' => $user->id]) }}">Remove</a>
                            @else
                                <a class="owner">Group Owner</a>
                                <form method="post" action="{{ route('group.delete',  ['groupid' => $group->groupid]) }}">
                                    @csrf
                                    <button type="submit">Delete group</button>
                                </form>
                            @endif
                        @endif
                    @endforeach
                </ul>
            @endif

            @if($group->owners->contains(auth()->user()))
                <form id="users-search-bar" action="{{ route('users.search') }}" method="GET">
                    @csrf
                    <input type="text" id="search-users" name="query" placeholder="🔍 Search users ..." list="usernames-list" autocomplete="off">
                    <datalist id="usernames-list"></datalist>
                </form>
                <ul id="users-list"></ul>
                <form id="addMemberForm" action="{{ route('group.addUser', ['groupid' => $group->groupid, 'userid' => $user->id]) }}" method="POST">
                    @csrf
                    <button id="addUserBtn">Add Member</button>
                </form>
            @else
            <form id="leaveGroupForm" action="{{ route('groups.leave', ['groupid' => $group->groupid]) }}" method="POST">
                @csrf
                <button type="submit">Leave Group</button>
            </form>
            @endif
            
    </div>
    </div>
    <link href="{{ url('css/group.css') }}" rel="stylesheet">
    <script src="{{ asset('js/editGroup.js') }}" defer></script>
@endsection


