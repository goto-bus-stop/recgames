@extends('layouts.main')

@section('content')
  <div class="section">
    <div class="container" style="max-width: 800px">
      <div class="tabs is-centered">
        <ul>
          <li>
            <a href="{{ route('login') }}">Log in</a>
          </li>
          <li>
            <a href="{{ route('register') }}">Create Account</a>
          </li>
          <li class="is-active">
            <a href="#">Forgot Password?</a>
          </li>
        </ul>
      </div>

      @if (session('status'))
        <div class="notification is-success">
          {{ session('status') }}
        </div>
      @endif

      <form role="form" method="POST" action="{{ route('password.email') }}">
          {{ csrf_field() }}

        <div class="field is-horizontal {{ $errors->has('email') ? ' has-error' : '' }}">
          <div class="field-label">
            <label for="email" class="label">E-Mail Address</label>
          </div>

          <div class="field-body">
            <div class="field">
              <div class="control">
                <input id="email"
                      type="email"
                      class="input @if ($errors->has('email')) is-danger @endif"
                      name="email"
                      value="{{ old('email') }}"
                      required>
              </div>

              @if ($errors->has('email'))
                <div class="help is-danger">
                  {{ $errors->first('email') }}
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label"></div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <button type="submit" class="button is-primary">
                  Send Password Reset Link
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
