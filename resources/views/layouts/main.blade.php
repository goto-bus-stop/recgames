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

  <footer class="footer">
    <div class="container">
      <p class="has-text-centered">
        recgam.es · <a href="https://github.com/goto-bus-stop/recgames">github</a>
      </p>
    </div>
  </footer>

  <script>
    recgames = {!!
      json_encode([
        'upload' => action('GamesController@upload'),
        'api' => [
          'recordedGames' => [
            'create' => action('API\\GamesController@create'),
            'upload' => action('API\\GamesController@upload', '%ID%'),
          ],
        ],
      ])
    !!}
  </script>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
