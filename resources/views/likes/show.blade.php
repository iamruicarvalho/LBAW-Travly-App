<!-- resources/views/likes/show.blade.php -->


@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
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
                <a href="{{ route('profile.show', auth()->id())  }}">👤 {{ auth()->user()->username }}</a>
            </div>
        </div>

        <div class="messages-show">
            <div class="header">
                <h1>Likes</h1>
                <div class="new-message-link">
                    <a href="#">❤️</a>
                </div>
            </div>
            <div class="conversations-list">
                @forelse($likers as $liker)
                    <div class="conversation-item">
                        <div class="user-avatar"></div>
                        <div class="conversation-details">
                            <p class="user-name">{{ $liker->user ? $liker->user->name_ : 'Not Found' }}</p>
                            <p class="username">{{ $liker->user ? $liker->user->username : '' }}</p>
                        </div>
                    </div>
                    </a>
                    @empty
                    <li class="no-likes-message">No likes yet.</li>
                @endforelse
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="right-sidebar">
            <div class="search-bar">
                {{-- Your search bar HTML goes here --}}
                <input type="text" placeholder="🔍 Search...">
            </div>
            <div class="suggested-groups">
            <!-- Your suggested groups content goes here -->
            <h3>Suggested Groups</h3>
            <!-- Display suggested groups -->
            <ul>
                <li>
                <div class="group-item">
                    <img src="https://visitworld.today/media/blog/previews/KXyZ9HqVwd5zhbBvkREQrjJTLMRQ9JQW8VETjX23.webp" alt="Italia Lovers Avatar">
                    <div class="group-details">
                    <p class="group-name">Italia Lovers</p>
                    <button class="join-button">Join</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="group-item">
                    <img src="https://static.independent.co.uk/2022/08/24/12/iStock-1146262403.jpg?width=1200&height=900&fit=crop" alt="I heart Japan Avatar">
                    <div class="group-details">
                    <p class="group-name">I heart Japan</p>
                    <button class="join-button">Join</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="group-item">
                    <img src="https://heymondo.com/blog/wp-content/uploads/2022/03/10_tips_to-travel-on-a-budget.jpg" alt="Budget Travel Avatar">
                    <div class="group-details">
                    <p class="group-name">Budget travel</p>
                    <button class="join-button">Join</button>
                    </div>
                </div>
                </li>
                <a href="{{ route('explore') }}" class="see-more-link">See More</a>

                <!-- Add more suggested groups as needed -->
            </ul>
            </div>

            <div class="people-near-you">
            <!-- Your people near you content goes here -->
            <h3>Trending Users</h3>
            <!-- Display people near you -->
            <ul>
                <li>
                <div class="person-item">
                    <img src="https://bgn2018media.s3.amazonaws.com/wp-content/uploads/2021/08/27082436/gettyimages-1201669316-612x612-1.jpg" alt="Bessie Cooper Avatar">
                    <div class="person-details">
                    <p class="person-name">Bessie Cooper</p>
                    <button class="follow-button">Follow</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="person-item">
                    <img src="https://img.freepik.com/free-photo/woman-mountain-with-travel-bag-looking-map_1303-11185.jpg" alt="Olivia Silva Avatar">
                    <div class="person-details">
                    <p class="person-name">Olivia Silva</p>
                    <button class="follow-button">Follow</button>
                    </div>
                </div>
                </li>
                <li>
                <div class="person-item">
                    <img src="https://jooinn.com/images/travelling-boy-1.jpg" alt="Joseph Martini Avatar">
                    <div class="person-details">
                    <p class="person-name">Joseph Martini</p>
                    <button class="follow-button">Follow</button>
                    </div>
                </div>
                </li>
                <a href="{{ route('explore') }}" class="see-more-link">See More</a>

                <!-- Add more people as needed -->
            </ul>
            </div>

            <div class="trending-hashtags">
                {{-- Your trending hashtags content goes here --}}
                <h3>Trending Hashtags</h3>
                <!-- Display trending hashtags -->
                <ul>
                    <li>#Travel</li>
                    <li>#Adventure</li>
                    <li>#Explore</li>
                    <!-- Add more hashtags as needed -->
                </ul>
                <a href="{{ route('explore') }}" class="see-more-link">See More</a>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css">

    <link href="{{ url('css/likes_list.css') }}" rel="stylesheet">
@endsection
