<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <link rel="icon" type="image/png" href="https://github.com/acarolinacc/teste/blob/main/plane.png?raw=true">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">

        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
        <header style="display: flex; justify-content: space-between; align-items: center;">
        <div class="logo">
            <h1><a href="{{ route('home') }}" class="white-link">Travly ✈</a></h1>
        </div>

            <nav class="nav-links">
                <ul>
                    <li><a href="{{ url('/about') }}">About</a></li>
                    <li><a href="{{ url('/help') }}">Help</a></li>
                    <li><a href="{{ url('/faq') }}">FAQ</a></li>
                    <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                </ul>
            </nav>
            @if (Auth::check())
            <div class="user-info">
                <a class="logout-button" href="{{ url('/logout') }}">
                    <img src="https://github.com/acarolinacc/teste/blob/main/logout%20(2).png?raw=true" alt="Logout">
                </a>
            </div>
            @endif
        </header>


            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>