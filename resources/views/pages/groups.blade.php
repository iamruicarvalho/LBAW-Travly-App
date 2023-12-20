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
@endsection


