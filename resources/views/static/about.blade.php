@extends('layouts.app')

@section('content')
    <div class="about-container">
        @if (Auth::check())
            <a href="{{ route('home') }}"> < Go back home</a>
        @else
            <a href="{{ route('login') }}"> < Go back home</a>
        @endif        
        <h1> About Travly </h1> 
        <h2>Welcome to Travly - Your Passport to Adventure!</h2>
        <p>In a world where digital connectivity is an integral part of our daily lives, we introduce Travly, a groundbreaking social network exclusively crafted for passionate travelers. Born from our love for exploration and the deep-seated desire to foster genuine connections among travel enthusiasts, Travly is not just a platform; it's a community dedicated to the spirit of adventure.</p>
        <h3>Our Vision</h3>
        <p>At Travly, we recognize that the true essence of travel often gets lost in the noise of conventional social media platforms. We are on a mission to change that. Our platform is designed to be a space where travelers can authentically share their journeys, adorned with unique details, and connect with like-minded explorers to form meaningful bonds.</p>
        <h3>Features That Set Us Apart</h3>
        <ol>
            <li><strong>Captivating Travel Stories:</strong> Share your adventures with engaging photos and narratives, allowing you to relive and others to be inspired by your experiences.</li>
            <li><strong>Destination Diaries:</strong> Write about the destinations you've visited, creating a rich tapestry of information for fellow travelers.</li>
            <li><strong>Personalized Travel Lists:</strong> Curate your own travel lists and share your must-visit places with the community.</li>
            <li><strong>User Privacy Management:</strong> Your privacy matters. You control who sees your information and posts, ensuring a secure and trusted environment.</li>
            <li><strong>Social Interaction:</strong> Connect with other travelers, express appreciation through "likes," and engage in discussions through comments on posts.</li>
            <li><strong>Advanced Search Features:</strong> Discover new destinations and experiences tailored to your personal interests.</li>
        </ol>
        <h3>Join the Travly Community Today!</h3>
        <p>Embrace the joy of travel, celebrate diverse cultures, and connect with fellow adventurers. Register now with your email or social media account and start creating memories that last a lifetime. Customize your profile, showcase your unique personality, and let the journey unfold on Travly.</p>
        <p><em>Adventure awaits - Let's explore together!</em></p>
    </div>
    <link href="{{ url('css/about.css') }}" rel="stylesheet">
@endsection