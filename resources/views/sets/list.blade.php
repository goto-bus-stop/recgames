@extends('layouts.main')

@section('title', 'sets')

@section('content')
  <section class="section">
    <div class="container">
      {{ $sets->links() }}
    </div>
    <br>
    <div class="container">
      @foreach ($sets as $set)
        <div class="box">
          <div class="media">
            <div class="media-left">
              @if ($set->thumbnailGame)
                <figure class="image">
                  <img src="{{ asset($set->thumbnailGame->minimap_url) }}"
                       style="width: 300px">
                </figure>
              @endif
            </div>
            <div class="media-content">
              <div class="content">
                <h4>{{ $set->title }}</h4>
                <p>{{ $set->description }}</p>
              </div>
              <div class="content">
                <p>
                  <strong>Games</strong> {{ $set->recorded_games_count }}
                </p>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <br>
    <div class="container">
      {{ $sets->links() }}
    </div>
  </section>
@endsection
