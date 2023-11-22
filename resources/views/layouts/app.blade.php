<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
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
        <header>
            <div class="logo">
                <h1><a href="{{ url('/cards') }}">Travly ✈︎</a></h1>
            </div>
            <nav class="nav-links">
                <ul>
                    <li><a href="{{ url('/about') }}">About</a></li>
                    <li><a href="{{ url('/services') }}">Services</a></li>
                    <li><a href="{{ url('/faq') }}">FAQ</a></li>
                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </nav>
            @if (Auth::check())
                <div class="user-info">
                    <a class="button" href="{{ url('/logout') }}"> Logout </a>
                    <a class="button" href="{{ route('profile.show', Auth::user()->username) }}"> {{ Auth::user()->username }} </a>
                    <!-- <div>{{ Auth::user()->username }}</div> -->
                </div>
            @endif
        </header>

            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>