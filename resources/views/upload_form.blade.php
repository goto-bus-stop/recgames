@extends('layouts.main')

@section('title', 'Upload Game')

@section('content')
    @if ($errors)
        <ul>
            @foreach ($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form action="{{ action('GamesController@upload') }}"
            method="POST"
            enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="recorded_game">
        <button type="submit">
            Upload
        </button>
    </form>
@endsection