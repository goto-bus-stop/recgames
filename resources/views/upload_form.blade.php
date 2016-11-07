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
          id="upload-form">
      {{ csrf_field() }}
      <label class="label">Recorded Game File</label>
      <p class="control">
        <input class="input" type="file" name="recorded_game" id="upload-file">
      </p>
      <p class="control">
        <button class="button is-primary" type="submit" id="upload-button">
          Upload
        </button>
      </p>
      <p class="control is-hidden">
        <progress class="progress" id="upload-progress"></progress>
      </p>
    </form>
  </div>
@endsection
