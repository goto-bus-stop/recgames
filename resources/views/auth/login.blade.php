@extends('layouts.main')

@section('title', 'Log in')

@section('content')
  <div class="section">
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading">Log in</div>
        <p class="panel-tabs">
          <a class="is-active" href="#">Log in</a>
          <a href="{{ route('register') }}">Create Account</a>
          <a href="{{ route('password.reset') }}">Forgot Password?</a>
        </p>
        <div class="panel-block">
          @if ($errors->has('social'))
            <div class="notification is-danger">
              {{ $errors->first('social') }}
            </div>
          @endif

          <div class="columns has-text-centered">
            <div class="column">
              <a href="{{ action('Auth\SocialiteController@steamRedirect') }}"
                 class="SocialAuthButton is-steam">
                <i class="SocialAuthButton-icon fa fa-steam" aria-hidden="true"></i> Sign in with Steam
              </a>
              <a href="{{ action('Auth\SocialiteController@twitchRedirect') }}"
                 class="SocialAuthButton is-twitch">
                <i class="SocialAuthButton-icon fa fa-twitch" aria-hidden="true"></i> Sign in with Twitch
              </a>
            </div>
          </div>

          <form role="form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

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
              <div class="control-label"></div>
              <div class="control">
                <label class="checkbox">
                  <input type="checkbox" name="remember">
                  Remember Me
                </label>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control-label"></div>
              <div class="control is-grouped">
                <div class="control">
                  <button type="submit" class="button is-primary">Login</button>
                </div>
                <div class="control">
                  <a class="button is-link" href="{{ route('password.reset') }}">
                    Forgot Your Password?
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
