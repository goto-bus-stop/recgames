@extends('layouts.main')

@section('title', 'Recorded Game')

@section('content')
  <div class="section">
    <div class="container">
      <div class="notification">
        <p>
          The analysis of this game could not be found. The analysis will be
          reattempted in a few moments.
        </p>
        <p>
          In the mean time, this recorded game can still be downloaded:
        </p>
        <p>
          <a class="button"
             href="{{ action('GamesController@download', $rec->slug) }}">
            Download
          </a>
        </p>
      </div>
    </div>
  </div>
@endsection
