@inject('helpers', 'App\Services\BladeHelpersService')

@extends('layouts.main')

@section('title', $title)

@push('head')
  <meta property="og:url"         content="{{ action('GamesController@show', $rec->slug) }}">
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="{{ $title }}">
  <meta property="og:image"       content="{{ asset($rec->minimap_url) }}">
  <meta property="og:image:type"  content="image/png">
  <meta property="og:site_name"   content="recgam.es">
@endpush

@section('content')
  {!! $html !!}

  <section class="section">
    <div class="container" id="disqus_thread"></div>
  </section>
  <script>
    var disqus_config = function () {
      this.page.title = {!! json_encode($title) !!};
      this.page.url = {!! json_encode(action('GamesController@show', $rec->slug)) !!};
      this.page.identifier = {!! json_encode($rec->slug) !!};
    };
    (function() {
      var d = document, s = d.createElement('script');
      s.src = '//recgames.disqus.com/embed.js';
      s.setAttribute('data-timestamp', +new Date());
      (d.head || d.body).appendChild(s);
    })();
  </script>
  <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
@endsection
