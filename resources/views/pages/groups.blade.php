@extends('layouts.app')

@section('content')

<div class="groups-container">

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
    <div class="search-container">
        <form id="groups-search-bar" action="{{ route('groups.search') }}" method="GET">
            @csrf
            <input type="text" id="search-groups" name="query" placeholder="🔍 Search groups ..." autocomplete="off" class="search-input">
        </form>
        <ul id="groups-list" class="groups-list"></ul>
    </div>
    <div class="groups">
    <h2> Your Groups </h2>
    <ul>
        @forelse ($groups as $group)
            <li><a href="{{  route('groups.show', $group->groupid) }}"> {{ $group->name_ }} </a></li>
        @empty
            <p>No groups found.</p>
    </ul>
        @endforelse
        <a class="create-group" href="{{  route('groups.showcreate') }}">Create new group</a>
    </div>
    </div>

    <link href="{{ url('css/groups.css') }}" rel="stylesheet">
    <script src="{{ asset('js/searchGroups.js') }}" defer></script>
@endsection


