@extends('layouts.app')

@section('content')
    <div class="help-container">
        <a href="{{ route('home') }}"> < Go back home</a>
        <h1>Need some help?</h1>
        <div class="getting-started">
            <h2>Welcome to Travly<br>Get started with our platform in a few easy steps!</h2>
            <div class="create-account">
                <h3>Create Your Account</h3>
                <p>Follow these steps to create a new account:</p>
                <ol>
                    <li>Visit our website at [yourwebsite.com].</li>
                    <li>Click on the "Sign Up" or "Create Account" button.</li>
                    <li>Fill in the required information, including your username, email, and password.</li>
                    <li>Complete your profile by adding a profile picture and cover photo.</li>
                    <li>Review and adjust your privacy settings as needed.</li>
                </ol>
            </div>
            <div class="profile-setup">
                <h3>Profile Setup</h3>
                <p>After creating your account, personalize your profile:</p>
                <div class="setup">
                    <p><strong>Add Profile Picture:</strong> Upload a photo to represent yourself.</p>
                    <p><strong>Complete Bio:</strong> Share a bit about yourself in the bio section.</p>
                    <p><strong>Cover Photo:</strong> Customize your profile with a cover photo.</p>
                </div>
            </div>
            <div class="platform-overview">
                <h3>Explore the Platform</h3>
                <p>Now that your account is set up, here are some key features to explore:</p>
                <div class="overview-list">
                    <p><strong>Profile Setup:</strong> Customize your profile by adding a bio, location, and more.</p>
                    <p><strong>Posting Content:</strong> Share your thoughts, images, and videos with your friends.</p>
                    <p><strong>Connecting with Others:</strong> Find and connect with friends or follow interesting users.</p>
                    <p><strong>Groups:</strong> Join or create groups based on your interests.</p>
                </div>
            </div>
        </div>
        <div class="privacy-security">
            <h2>Privacy and Security</h2>
            <div class="account-privacy">
                <p>When you register in Travly, your account is set to private, meaning that only users that follow you can see your account.<br>You can change this by editing your profile and set your account to public, which will allow any user in the app to see your account.</p>
                <p>If your account is private, your posts will be only visible to those who follow you. Likes and comments on tour post can be only made by your followers.<br>Otherwise, anyone will be able to see, like and comment on your posts.</p>
            </div>
            <div class="password-strongness">
                <p>We suggest you to create an account with a strong and unique password for account security.</p>
            </div>
            <div class="data-protection">
                <p>All of the users data is protected and stored in a private database. Sensitive data such as passwords is encrypted to ensure confidentiality.</p>
            </div>
            <p>If your account is private, your posts will be only visible to those who follow you. Otherwise, anyone will be able to see your posts</p>
        </div>
        <div class="technical-support">
            <h2>Need Help?</h2>
            <p>If you encounter any issues or have questions, our support team is here to help:</p>
            <p><strong>Contact Support: <a href="#">support@example.com</a></strong></p>
        </div>
        <div class="terms-of-service">
            <h2>Terms of Service</h2>
            <p>Using Travly, you agree to abide by the Terms of Service.<br>Continued use of the service constitutes acceptance of any updates or changes to the terms.</p>
            <p>You must be 18+ years old to be create an account in Travly.</p>
            <p>When creating an account, you should maintain the confidentiality of your account credentials.</p>
            <p>A user retains ownership of his content but, at the same time, grants the platform a license to use, modify, or distribute his content within the scope of the service</p>
            <p>User behavior and conduct on the platform must be respectful to the others.</p>
            <a href="{{ route('static.privacy_policy') }}">Take a look at the privacy policy.</a>
        </div>
        <div class="feedback">
            <h2>Feedback</h2>
            <p>Travly cares about their users and their opinions.<br>Tell us what you like and what could be possibly improved so that you can enjoy the app even more.</p>
            <input name="feedback" placeholder="Give us your feedback">
        </div>
    </div>
    <link href="{{ url('css/help.css') }}" rel="stylesheet">
@endsection