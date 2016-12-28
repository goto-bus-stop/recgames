<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  @stack('head')

  <title>@yield('title') · recgam.es</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="nojs">
  {{-- UI State --}}
  <input id="s-menu-open" type="checkbox" class="is-hidden" name="{{ uniqid() }}">

  <nav class="nav has-shadow">
    <div class="container">
      <div class="nav-left">
        <a href="{{ url('/') }}" class="nav-item is-brand">
          recgam.es
        </a>
      </div>

      <label class="nav-toggle" for="s-menu-open">
        <span></span>
        <span></span>
        <span></span>
      </label>

      <div class="nav-right nav-menu">
        <a class="nav-item" href="{{ action('GamesController@list') }}">Games</a>
        <a class="nav-item" href="{{ action('SetsController@list') }}">Sets</a>
        @if (Auth::check())
          <a class="nav-item" href="{{ url('/profile') }}">{{ Auth::user()->name }}</a>
        @else
          <a class="nav-item" href="{{ route('login') }}">Log in</a>
          <a class="nav-item" href="{{ route('register') }}">Create Account</a>
        @endif
        <span class="nav-item">
          <a class="button is-primary" href="{{ action('GamesController@upload') }}">
            <span>Upload</span>
          </a>
        </span>
      </ul>
    </div>
  </nav>

  @yield('content')

  <footer class="footer">
    <div class="container">
      <p class="has-text-centered">
        recgam.es · <a href="https://github.com/goto-bus-stop/recgames">github</a>
      </p>
      <p class="has-text-centered">
        Support recgam.es hosting and development by <a href="https://paypal.me/recgames">making a donation</a>!
      </p>
    </div>
  </footer>

  @include('components.api_urls')
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
