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
<body class="nojs is-embed">
  @yield('content')

  <footer class="footer" style="padding: 20px; margin-top: 20px">
    <div class="container">
      <p class="has-text-centered">
        <a href="{{ url('/') }}">Hosted on recgam.es</a>
      </p>
    </div>
  </footer>

  @include('components.api_urls')
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
