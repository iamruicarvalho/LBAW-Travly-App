@extends('layouts.app')

@section('content')

<div class="groups-container">

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

    <div class="Group-container">
    <h2> {{ $group->name_ }}</h2>
    
            <p> Description: {{ $group->description_ }}</p>
 
    </div>

    </div>
    <link href="{{ url('css/groups.css') }}" rel="stylesheet">
@endsection


