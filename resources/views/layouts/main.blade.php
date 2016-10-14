<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>@yield('title') · recgam.es</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper container">
                <a href="{{ url('/') }}" class="brand-logo">recgames</a>
                <ul class="right">
                    <li><a href="{{ url('/') }}">Games</a></li>
                    <li><a class="waves-effect btn" href="{{ action('GamesController@upload') }}">
                        Upload
                    </a></li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
