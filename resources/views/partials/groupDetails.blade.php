@extends('layouts.app')

@section('content')

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Include your custom JavaScript file -->
<script src="{{ asset('js/editGroup.js') }}"></script>

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
            <div>
                <input type="text" id="userSearch" placeholder="Search for a member">
                <div id="autocompleteSuggestions"></div>
                <button id="addUserBtn">Add Member</button>
            </div>
            @else
                <a class="leave-group" data-group-id="{{ $group->groupid }}" data-user-id="{{ auth()->user()->id }}">Leave Group</a>
            @endif
            
    </div>
    </div>
    <link href="{{ url('css/group.css') }}" rel="stylesheet">
@endsection


