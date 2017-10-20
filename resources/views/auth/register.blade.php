@extends('layouts.main')

@section('title', 'Register')

@section('content')
  <div class="section">
    <div class="container" style="max-width: 800px">
      <div class="tabs is-centered">
        <ul>
          <li>
            <a href="{{ route('login') }}">Log in</a>
          </li>
          <li class="is-active">
            <a href="#">Create Account</a>
          </li>
          <li>
            <a href="{{ route('password.reset') }}">Forgot Password?</a>
          </li>
        </ul>
      </div>

      <form role="form" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="field is-horizontal">
          <div class="field-label">
            <label for="name" class="label">Name</label>
          </div>

          <div class="field-body">
            <div class="field">
              <div class="control">
                <input id="name"
                      type="text"
                      class="input @if ($errors->has('name')) is-danger @endif"
                      name="name"
                      value="{{ old('name') }}"
                      required
                      autofocus>
              </div>

              @if ($errors->has('name'))
                <span class="help is-danger">
                  {{ $errors->first('name') }}
                </span>
              @endif
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
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
                <span class="help is-danger">
                  {{ $errors->first('email') }}
                </span>
              @endif
              <span class="help is-dark">
                Optional. You won't be able to reset your password if you do not provide an email address.
              </span>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label">
            <label for="password" class="label">Password</label>
          </div>

          <div class="field-body">
            <div class="field">
              <div class="control">
                <input id="password"
                      type="password"
                      class="input @if ($errors->has('password')) is-danger @endif"
                      name="password"
                      required>
              </div>

              @if ($errors->has('password'))
                <span class="help is-danger">
                  {{ $errors->first('password') }}
                </span>
              @endif
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label">
            <label for="password-confirm" class="label">Confirm Password</label>
          </div>

          <div class="field-body">
            <div class="field">
              <div class="control">
                <input id="password-confirm"
                       type="password"
                       class="input"
                       name="password_confirmation"
                       required>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label"></div>
          <div class="field-body">
            <div class="control">
              <button type="submit" class="button is-primary">
                Register
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
