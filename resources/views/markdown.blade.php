@inject('parser', 'App\Services\MarkdownService')

@extends('layouts.main')

@section('title', $title)

@section('content')
  <div class="section">
    <div class="container">
      <div class="content">
        {!! $parser->transform($source) !!}
      </div>
    </div>
  </div>
@endsection
