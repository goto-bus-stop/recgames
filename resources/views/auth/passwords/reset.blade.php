@extends('layouts.main')

@section('title', 'Reset Password')

@section('content')
  <div class="section">
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">Reset Password</div>
        <div class="panel-block">
          @if (session('status'))
            <div class="notification is-success">
              {{ session('status') }}
            </div>
          @endif

          <form role="form" method="POST" action="{{ route('password.reset') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

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
                        value="{{ $email or old('email') }}"
                        required
                        autofocus>

                  @if ($errors->has('email'))
                    <span class="help is-danger">
                      {{ $errors->first('email') }}
                    </span>
                  @endif
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
              <div class="control is-grouped">
                <div class="control is-expanded">
                  <input id="password-confirm"
                          type="password"
                          class="input @if ($errors->has('password_confirmation')) is-danger @endif"
                          name="password_confirmation"
                          required>

                  @if ($errors->has('password_confirmation'))
                    <span class="help is-danger">
                      {{ $errors->first('password_confirmation') }}
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control-label"></div>
              <div class="control">
                <button type="submit" class="button is-primary">Reset Password</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
