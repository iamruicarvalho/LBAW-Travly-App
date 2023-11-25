@extends('layouts.app')

@section('content')
    <a href="{{ route('home') }}"> < Go back home</a>
    <div class="faq-container">
        <h1>FAQ - Travly</h1>

        <h2>What is Travly?</h2>
        <p>Travly is a social platform designed to connect people that love to travel, share experiences, and facilitate communication among its users.</p>

        <h2>How can I sign up for Travly?</h2>
        <p>To sign up, click on the "Sign Up" button on the homepage, fill in the required information, and follow the prompts to create your account.</p>

        <h2>Is it free to use Travly?</h2>
        <p>Yes, signing up and using basic features of Travly are free.</p>

        <h2>How do I create a new post?</h2>
        <p>After logging in, locate the "Create Post" button on the dashboard. Click it, write your post, and optionally add media content. Once done, click "Post" to share it with your network.</p>

        <h2>Can I customize my profile?</h2>
        <p>Yes, you can customize your profile by adding a profile picture, cover photo, and bio. Navigate to your profile, click on "Edit Profile," and update the relevant information.</p>

        <h2>How do I connect with others?</h2>
        <p>You can connect with others by sending friend requests. Visit a user's profile, click on the "Add Friend" button, and wait for the request to be accepted.</p>

        <h2>What types of media can I share in posts?</h2>
        <p>Travly supports various media types, including images, videos, and text. When creating a post, you can upload media files directly from your device.</p>

        <h2>How can I control who sees my posts?</h2>
        <p>You can set the privacy settings for each post. Options usually include Public, Friends Only, or Only Me. Choose the desired setting before publishing your post.</p>

        <h2>What notifications will I receive?</h2>
        <p>You will receive notifications for friend requests, likes, comments on your posts, and other relevant activities. You can customize your notification preferences in the settings.</p>

        <h2>How do I report inappropriate content or users?</h2>
        <p>If you come across inappropriate content or behavior, use the reporting feature available on posts or profiles. The moderation team will review the report and take appropriate action.</p>

        <h2>Can I delete my account?</h2>
        <p>Yes, you can delete your account. Go to the account settings, find the option to delete your account, and follow the instructions. Keep in mind that this action is irreversible.</p>


    </div>
    <link href="{{ url('css/faq.css') }}" rel="stylesheet">
@endsection
