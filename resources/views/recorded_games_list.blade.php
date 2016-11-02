@extends('layouts.main')

@section('title', 'list')

@section('content')
  <section class="section">
    <div class="container">
      {{ $recordings->links() }}
    </div>
    <br>
    <div class="container">
      <div class="columns is-multiline">
        @each('components.recorded_game_card', $recordings, 'rec', 'components.no_results')
      </div>
    </div>
    <div class="container">
      {{ $recordings->links() }}
    </div>
  </section>
@endsection
