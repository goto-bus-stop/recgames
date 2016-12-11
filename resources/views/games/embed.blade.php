@extends('layouts.embedded')

@section('title', 'Recorded Game')

@push('head')
  <link rel="canonical" href="{{ action('GamesController@show', $rec->slug) }}">
@endpush

@section('content')
  {!! $html !!}
@endsection
