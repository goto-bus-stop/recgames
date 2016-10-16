@extends('layouts.main')

@section('title', 'list')

@section('content')
  <section class="section">
    <div class="container">
      {{ $recordings->links() }}
    </div>
    <br>
    <div class="container columns is-multiline is-mobile">
      @each('components.recorded_game_card', $recordings, 'rec')
    </div>
    <div class="container">
      {{ $recordings->links() }}
    </div>
  </section>
@endsection
