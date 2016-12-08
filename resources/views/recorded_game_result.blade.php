@extends('layouts.main')

@section('title', $title)

@push('head')
  <meta property="og:url"         content="{{ action('GamesController@show', $rec->slug) }}">
  <meta property="og:type"        content="article">
  <meta property="og:title"       content="{{ $title }}">
  <meta property="og:image"       content="{{ asset($rec->minimap_url) }}">
  <meta property="og:image:type"  content="image/png">
@endpush

@section('content')
    {!! $html !!}
@endsection
