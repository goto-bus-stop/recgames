@extends('layouts.main')

@section('title', 'Recorded Game')

@section('content')
    <div class="section">
      <div class="notification">
        <p>
          Analysis of this game failed. This might be a bug in the analyzer. The
          analysis will be retried when new versions of the analyzer are
          released.
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
@endsection
