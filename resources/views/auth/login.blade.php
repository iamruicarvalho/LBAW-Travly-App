/*
@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection
*/
// antigo login.blade.php


<!-- login.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="login">
        <div class="card">
            <div class="left">
                <h1>Travly</h1>
                <p>
                    Your traveler's community, where sharing, exploring, 
                    and finding inspiration in global adventures becomes a reality.
                </p>
                <span>Don't you have an account?</span>
                <a href="{{ route('register') }}">
                    <button>Register</button>
                </a>
            </div>
            <div class="right">
                <h1>Login</h1>
                <form method="POST" action="{{ route('login') }}">
                @csrf
                <input
                    type="email" 
                    placeholder="E-mail"
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
                    @error('username')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
@endsection
