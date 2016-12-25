@extends('layouts.main')

@section('title', 'Not Found')

@section('content')
  <div class="section">
    <div class="container">
      <h1 class="title">Not Found</h1>

      <div class="notification is-danger">
        <p>{{ $exception->getMessage() }}</p>
      </div>
    </div>
  </div>
@endsection
