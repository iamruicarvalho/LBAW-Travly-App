<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('styles.css') }}" /> 
        <link href="{{ url('css/register.css') }}" rel="stylesheet">
        <title>Travly | Social Network</title>
    </head>
    <body>
        <div class="container">
            <nav>
                <div class="nav__logo">
                    <img src="{{ asset('https://github.com/acarolinacc/teste/blob/main/logo.png?raw=true') }}" alt="logo" /> 
                </div>
                <ul class="nav__links">
                    <li class="link"><a href="{{ url('/about') }}">About</a></li>
                    <li class="link"><a href="{{ url('/help') }}">Help</a></li>
                    <li class="link"><a href="{{ url('/faq') }}">FAQ</a></li>
                    <li class="link"><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                    <a href="{{ route('guest') }}" class="guest-link">Enter as Guest</a>
                </ul>

            </nav>
            <div class="destination__container">
                <img class="bg__img__1" src="{{ asset('https://github.com/acarolinacc/teste/blob/main/bg-dots.png?raw=true') }}" alt="bg" /> 
                <img class="bg__img__2" src="{{ asset('https://github.com/acarolinacc/teste/blob/main/bg-arrow.png?raw=true') }}" alt="bg" /> 
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
                <form class="register-form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="error">
                            {{ $errors->first('name') }}
                        </span>
                    @endif

                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
                    @if ($errors->has('username'))
                        <span class="error">
                            {{ $errors->first('username') }}
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

                    <div class="register-buttons">
                        <button type="submit">Register</button>
                        <a class="button button-outline" href="{{ route('login') }}">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
