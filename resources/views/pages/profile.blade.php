@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('styles.css') }}" /> 
    <title>Travly | Social Network</title>
</head>
<body>
    <div class="container">
        <nav>
            <div class="nav__logo">
                <img src="{{ asset('https://raw.githubusercontent.com/WebDesignMastery/Explore_Destination_28-06-23/main/assets/logo.png') }}" alt="logo" /> 
            </div>
            <ul class="nav__links">
                <li class="link"><a href="#">About</a></li>
                <li class="link"><a href="#">Help</a></li>
                <li class="link"><a href="#">FAQ</a></li>
                <li class="link"><a href="#">Privacy Policy</a></li>
            </ul>
        </nav>
        <div class="destination__container">
            <img class="bg__img__1" src="{{ asset('https://github.com/WebDesignMastery/Explore_Destination_28-06-23/blob/main/assets/bg-dots.png?raw=true') }}" alt="bg" /> 
            <img class="bg__img__2" src="{{ asset('https://github.com/WebDesignMastery/Explore_Destination_28-06-23/blob/main/assets/bg-arrow.png?raw=true') }}" alt="bg" /> 
            <div class="socials">
            </div>
            <div class="content">
                <h1>EXPLORE<br />CONNECT<br /><span>DISCOVER</span></h1>
                <p>
                Welcome to Travly, where every moment is a new adventure. 
                Dive into uncharted territories, connect with like-minded explorers, and let the journey be your destination. 
                Embrace diverse cultures, share your experiences, and ignite the wanderlust within you. 
                Travly is your passport to a world of discovery – join us and let the exploration begin!
                </p>
            </div>
            <div class="form-container">
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
            </div>
        </div>
    </div>
</body>
<link href="{{ url('css/login.css') }}" rel="stylesheet">
@endsection
