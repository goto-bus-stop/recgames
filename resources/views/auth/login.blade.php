@extends('layouts.main')

@section('title', 'Log in')

@section('content')
  <div class="section">
    <div class="container" style="max-width: 800px">
      <div class="tabs is-centered">
        <ul>
          <li class="is-active">
            <a href="#">Log in</a>
          </li>
          <li>
            <a href="{{ route('register') }}">Create Account</a>
          </li>
          <li>
            <a href="{{ route('password.reset') }}">Forgot Password?</a>
          </li>
        </ul>
      </div>

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
                        required
                        autofocus>
              </div>

              @if ($errors->has('email'))
                <span class="help is-danger">
                  {{ $errors->first('email') }}
                </span>
              @endif
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
          <div class="field-label"></div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <label class="checkbox">
                  <input type="checkbox" name="remember">
                  Remember Me
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label"></div>
          <div class="field-body">
            <div class="field is-grouped">
              <div class="control">
                <button type="submit" class="button is-primary">Login</button>
              </div>

              <div class="control">
                <a class="button is-text" href="{{ route('password.reset') }}">
                  Forgot Your Password?
                </a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
