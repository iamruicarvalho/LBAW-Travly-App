@extends('layouts.app')

@section('content')

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Include your custom JavaScript file -->
<script src="{{ asset('js/createGroup.js') }}"></script>


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
            <li><a href="{{ route('groups') }}">👥 Groups</a></li>
        </ul>
        <div class="profile-section">
            <!-- Profile information here -->
            <a href="{{  route('profile.show', auth()->id()) }}">👤 {{ auth()->user()->username }}</a>
        </div>
    </div>
    </div>

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
                <label for="userSearch">Add members to the group</label>
                <input type="text" id="userSearch" placeholder="Search for a member">
                <div id="autocompleteSuggestions"></div>
                <ul id="selectedMembersList"></ul> 
                <button type="button" id="addUserBtn">Add Member</button>
            </div>

            <div class="form-group">
                <a> Selected Members: </a>
                <ul id="selectedMembersList"></ul>
                <input type="hidden" name="selectedMembers" id="selectedMembers" value="">
            </div>
           
            <button type="submit" class="btn btn-primary">Create Group</button>
        </form>
    </div>
</div>
<link href="{{ url('css/group.css') }}" rel="stylesheet">

@endsection

