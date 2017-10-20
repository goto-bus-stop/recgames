@extends('layouts.main')

@section('title', 'Upload Game')

@section('content')
  @if (count($errors) > 0)
    <div class="section">
      <div class="container">
        <ul class="notification">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <div class="section">
    <form action="{{ action('GamesController@upload') }}"
          method="POST"
          enctype="multipart/form-data"
          class="container"
          style="max-width: 800px"
          id="upload-form">
      {{ csrf_field() }}
      <div class="field">
        <label class="label">Recorded Game File</label>
        <p class="control">
          <input class="input"
                 type="file"
                 name="recorded_game[]"
                 id="upload-file"
                 multiple>
        </p>
        <span class="help">Files can be recorded games, or zip archives containing recorded games.</span>
      </div>
      <div class="field">
        <p class="control">
          <button class="button is-primary" type="submit" id="upload-button">
            Upload
          </button>
        </p>
      </div>
      <div class="field">
        <p class="control is-hidden">
          <progress class="progress" id="upload-progress"></progress>
        </p>
      </div>
    </form>
  </div>
@endsection
