@extends('layouts.main')

@section('title', $set->title)

@section('content')
  <section class="section">
    <div class="container">
      <h1 class="title">{{ $set->title }}</h1>
      <p class="content">
        {{ $set->description }}
      </p>
    </div>

    <div class="container">
      {{ $recordings->links() }}
    </div>
    <br>
    <div class="container">
      <div class="columns is-multiline">
        @each('components.recorded_game_card', $recordings, 'rec', 'components.no_results')
      </div>
    </div>
    <br>
    <div class="container">
      {{ $recordings->links() }}
    </div>
  </section>
@endsection
