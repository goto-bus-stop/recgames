@extends('layouts.main')

@section('title', 'Register')

@section('content')
  <div class="section">
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">Create Account</div>
        <p class="panel-tabs">
          <a href="{{ route('login') }}">Log in</a>
          <a class="is-active" href="#">Create Account</a>
          <a href="{{ route('password.reset') }}">Forgot Password?</a>
        </p>
        <div class="panel-block">
          <form role="form" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="control is-horizontal">
              <div class="control-label">
                <label for="name" class="label">Name</label>
              </div>

              <div class="control is-grouped">
                <div class="control is-expanded">
                  <input id="name"
                        type="text"
                        class="input @if ($errors->has('name')) is-danger @endif"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus>

                  @if ($errors->has('name'))
                    <span class="help is-danger">
                      {{ $errors->first('name') }}
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control-label">
                <label for="email" class="label">E-Mail Address</label>
              </div>

              <div class="control is-grouped">
                <div class="control is-expanded">
                  <input id="email"
                        type="email"
                        class="input @if ($errors->has('email')) is-danger @endif"
                        name="email"
                        value="{{ old('email') }}"
                        required>

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

            <div class="control is-horizontal">
              <div class="control-label">
                <label for="password" class="label">Password</label>
              </div>

              <div class="control is-grouped">
                <div class="control is-expanded">
                  <input id="password"
                        type="password"
                        class="input @if ($errors->has('password')) is-danger @endif"
                        name="password"
                        required>

                  @if ($errors->has('password'))
                    <span class="help is-danger">
                      {{ $errors->first('password') }}
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control-label">
                <label for="password-confirm" class="label">Confirm Password</label>
              </div>

              <div class="control">
                <input id="password-confirm"
                       type="password"
                       class="input"
                       name="password_confirmation"
                       required>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control-label"></div>
              <div class="control">
                <button type="submit" class="button is-primary">
                  Register
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
