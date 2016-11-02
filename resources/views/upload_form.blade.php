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

  <script>
    function $ (sel, ctx) {
      return [].slice.call((ctx || document).querySelectorAll(sel))
    }
    $('#upload-form').forEach(function (form) {
      form.onsubmit = function (event) {
        event.preventDefault()
        $('#upload-button').forEach(function (button) {
          button.classList.add('is-loading')
          button.disabled = true
        })

        var progress = $('#upload-progress')[0]
        var files = $('#upload-file')[0].files
        progress.parentNode.classList.remove('is-hidden')

        progress.max = 100

        var formData = new FormData(form)

        var uploadUrl = {!! json_encode(action('GamesController@upload')) !!}
        var xhr = new XMLHttpRequest()
        xhr.open('POST', uploadUrl)
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
        xhr.onload = onload
        xhr.upload.onprogress = onprogress

        xhr.send(formData)

        function onprogress (event) {
          progress.value = (event.loaded / event.total) * 100
        }
        function onload () {
          window.location = JSON.parse(xhr.responseText).redirectUrl
        }
      }
    })
  </script>
@endsection
