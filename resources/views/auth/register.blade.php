/*
@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection
*/
// antigo register.blade.php


<!-- resources/views/auth/register.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="register">
        <div class="card">
            <div class="left">
                <h1>Lama Social.</h1>
                <p>
                  Your traveler's community, where sharing, exploring, 
                  and finding inspiration in global adventures becomes a reality.
                </p>
                <span>Do you have an account?</span>
                <a href="{{ route('login') }}">
                    <button>Login</button>
                </a>
            </div>
            <div class="right">
                <h1>Register</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input
                        type="text"
                        placeholder="Username"
                        name="username"
                        value="{{ old('username') }}"
                        required
                    />
                    <input
                        type="email"
                        placeholder="Email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    />
                    <input
                        type="password"
                        placeholder="Password"
                        name="password"
                        required
                    />
                    <input
                        type="text"
                        placeholder="Name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    />
                    @error('username')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
@endsection
