@extends('layouts.app')

@section('content')
    <div class="profile-container">
        <h1>Edit Profile</h1>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <!-- Add form fields for updating the user profile (e.g., name, email) -->
            <label for="name">Name</label>
            <input type="text" id="name" name="name">

            <label for="email">Email</label>
            <input type="email" id="email" name="email">

            <!-- Add other form fields as needed -->

            <button type="submit">Save Changes</button>
        </form>
    </div>
@endsection