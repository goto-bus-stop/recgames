<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>@yield('title') · recgam.es</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
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
        <a class="nav-item" href="{{ url('/') }}">Games</a>
        <span class="nav-item">
          <a class="button is-primary" href="{{ action('GamesController@upload') }}">
            <span>Upload</span>
          </a>
        </span>
      </ul>
    </div>
  </nav>

  @yield('content')
</body>
</html>
