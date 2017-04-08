@extends('layouts.main')

@section('title', $user->name)

@section('content')
  <div class="section">
    <div class="container">
      <h1 class="title">{{ $user->name }}</h1>
      <div class="columns">
        <div class="column">
          <h2 class="subtitle">Uploads</h2>
          @foreach ($user->uploaded()->withAnalysis()->limit(3)->get() as $rec)
            @include('components.recorded_game_card', [
              'rec' => $rec,
            ])
          @endforeach
        </div>
        <div class="column">
        </div>
      </div>
    </div>
  </div>
@endsection
