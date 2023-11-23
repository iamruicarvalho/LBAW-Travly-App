@extends('layouts.app')


@section('content')
    <div class="container">
        {{-- Left Sidebar --}}
        <div class="left-sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('home') }}">🏠 Home</a></li>
                <li><a href="#">🔍 Explore</a></li>
                <li><a href="#">🔔 Notifications</a></li>
                <li><a href="#">📨 Messages</a></li>
                <li><a href="#">🌎 Wish List</a></li>
                <li><a href="#">👥 Groups</a></li>
                <li><a href="#">➕ More</a></li>
            </ul>
            <div class="profile-section">
                <!-- Profile information here -->
                <a href="{{ route('profile.show') }}">👤 {{ auth()->user()->username }}</a>
                <!-- <a href="{{ route('profile.show', auth()->id()) }}">User Name</a> -->
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-content">
            {{-- Top Section --}}
            <div class="top-section">
                <div class="user-info">
                    <h2>🏠 Home</h2>
                </div>
            </div>
            <div class="privacy-policy">
                <pre>
                    Privacy Policy for Travly

                    At Travly, we are committed to safeguarding the privacy of our users. This Privacy Policy outlines how we collect, use, disclose, and store the information provided by users when they access or use our website.

                    Information Collection

                    When you visit Travly, we may collect personal information such as your name and email address when voluntarily provided by you, for example, when you subscribe to our newsletter, create an account, or use our contact form.

                    Use of Information

                    The information collected is used for the following purposes:

                    To personalize user experience and deliver content and features based on user interests.
                    To improve our website and services based on the feedback we receive.
                    To send periodic emails regarding updates, promotions, or other information related to Travly.
                    Protection of Information

                    We implement a variety of security measures to maintain the safety of your personal information. However, please note that no method of transmission over the internet or electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.

                    Disclosure of Information

                    We do not sell, trade, or otherwise transfer your personally identifiable information to outside parties. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, as long as those parties agree to keep this information confidential.

                    Third-Party Links

                    Occasionally, at our discretion, we may include or offer third-party products or services on our website. These third-party sites have separate and independent privacy policies. Therefore, we have no responsibility or liability for the content and activities of these linked sites.

                    Consent

                    By using our website, you consent to our Privacy Policy.

                    Changes to Privacy Policy

                    Travly reserves the right to update this Privacy Policy at any time. We encourage users to frequently check this page for any changes to stay informed about how we are helping to protect the personal information we collect.

                    If you have any questions regarding this Privacy Policy, you may contact us at contact@travly.com.
                </pre>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="right-sidebar">
            <div class="search-bar">
                {{-- Your search bar HTML goes here --}}
                <input type="text" placeholder="🔍 Search...">
            </div>
            <div class="suggested-groups">
                {{-- Your suggested groups content goes here --}}
                <h3>Suggested Groups</h3>
                <!-- Display suggested groups -->
                <ul>
                    <li>Italia Lovers</li>
                    <li>I heart Japan</li>
                    <li>Budget travel</li>
                    <!-- Add more suggested groups as needed -->
                </ul>
            </div>

            <div class="people-near-you">
                {{-- Your people near you content goes here --}}
                <h3>People Near You</h3>
                <!-- Display people near you -->
                <ul>
                    <li>Bessie Cooper</li>
                    <li>Olivia Silva</li>
                    <li>Joseph Martini</li>
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
            </div>
        </div>
    </div>
@endsection
