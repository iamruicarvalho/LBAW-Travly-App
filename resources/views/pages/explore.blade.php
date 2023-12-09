@extends('layouts.app')

@section('content')
    <div class="explore-container">
        <h1>Explore</h1>

        <!-- Advanced Search -->
        <div class="search-section">
            <form action="#" method="GET">
                <input type="text" name="query" placeholder="Search...">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Trends -->
        <div class="trending-section">
            <h2>Trending</h2>
            <ul>
                <li>#Travel</li>
                <li>#Photography</li>
                <li>#DeliciousFood</li>
                <li>#Art</li>
                <li>#Technology</li>
            </ul>
        </div>

        <!-- Content Discovery -->
        <div class="discovery-section">
            <h2>Content Discovery</h2>
            <div class="suggested-users">
                <div class="user-card">
                    <img src="user1.jpg" alt="User 1">
                    <p>User 1</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="user2.jpg" alt="User 2">
                    <p>User 2</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="user2.jpg" alt="User 2">
                    <p>User 2</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="user2.jpg" alt="User 2">
                    <p>User 2</p>
                    <button>Follow</button>
                </div>
                <div class="user-card">
                    <img src="user2.jpg" alt="User 2">
                    <p>User 2</p>
                    <button>Follow</button>
                </div>
                <!-- Add more user cards as needed -->
            </div>
        </div>

        <!-- Travel Inspiration -->
        <div class="travel-inspiration-section">
            <h2>Travel Inspiration</h2>
            <div class="country-grid">
                <div class="country-card">
                    <img src="france.jpg" alt="France">
                    <p>France</p>
                </div>
                <div class="country-card">
                    <img src="italy.jpg" alt="Italy">
                    <p>Italy</p>
                </div>
                <div class="country-card">
                    <img src="japan.jpg" alt="Japan">
                    <p>Japan</p>
                </div>
                <div class="country-card">
                    <img src="brazil.jpg" alt="Brazil">
                    <p>Brazil</p>
                </div>
                <!-- Add more country cards as needed -->
            </div>
        </div>
    <link href="{{ url('css/explore.css') }}" rel="stylesheet">
@endsection
