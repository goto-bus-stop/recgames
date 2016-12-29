@extends('layouts.main')

@section('title', 'Settings')

@section('content')
  <div class="section">
    <div class="container">
      <h1 class="title">Settings</h1>
      <h2 class="subtitle">Connections</h2>
      <div class="columns">
        <div class="column">
          <h3 class="subtitle">Local</h3>

          @if (session()->has('local'))
            <div class="notification is-success">
              {{ session('local') }}
            </div>
          @endif

          <div class="content">
            @if ($user->email)
              <p>
                You can change your email address and password below. You can also
                <a href="{{ action('ProfileController@removeLocalLogin') }}">disable email/password login</a>,
                if you would prefer to use Steam or Twitch login exclusively going forward.
                You can always add an email/password login combination to your recgam.es
                account again later.
              </p>
            @else
              <p>
                Your recgam.es account does not have an email/password login
                combination. If you want, you can add one below.
              </p>
            @endif
          </div>

          <form method="post" action="{{ action('ProfileController@changeLocalLogin') }}">
            {{ csrf_field() }}
            <label class="label">E-Mail Address</label>
            <div class="control">
              <input type="email"
                     class="input @if ($errors->has('email')) is-danger @endif"
                     name="email"
                     value="{{ $user->email }}"
                     placeholder="me@email.com">

                @if ($errors->has('email'))
                  <span class="help is-danger">
                    {{ $errors->first('email') }}
                  </span>
                @endif
            </div>
            @if ($user->email)
              <label class="label">Current Password</label>
              <div class="control">
                <input type="password"
                       class="input @if ($errors->has('password')) is-danger @endif"
                       name="password"
                       required>

                @if ($errors->has('password'))
                  <span class="help is-danger">
                    {{ $errors->first('password') }}
                  </span>
                @endif
              </div>
              <label class="label">New Password</label>
              <div class="control">
                <input type="password"
                       class="input @if ($errors->has('new_password')) is-danger @endif"
                       name="new_password"
                       placeholder="Minimum 6 characters">

                @if ($errors->has('new_password'))
                  <span class="help is-danger">
                    {{ $errors->first('new_password') }}
                  </span>
                @endif
              </div>
              <div class="control">
                <input type="password" class="input" name="new_password_confirmation" placeholder="Confirm New Password">
              </div>
            @else
              <label class="label">Password</label>
              <div class="control">
                <input type="password"
                       class="input @if ($errors->has('new_password')) is-danger @endif"
                       name="new_password"
                       placeholder="Minimum 6 characters">

                @if ($errors->has('new_password'))
                  <span class="help is-danger">
                    {{ $errors->first('new_password') }}
                  </span>
                @endif
              </div>
              <div class="control">
                <input type="password" class="input" name="new_password_confirmation" placeholder="Confirm Password">
              </div>
            @endif
            <button class="button is-primary" type="submit">Update</button>
          </form>
        </div>
        <div class="column">
          <h3 class="subtitle">Twitch</h3>
          @if ($user->twitch_id)
            <div class="content">
              <p>
                Connected with Twitch.
              </p>
              <p>
                <a href="{{ action('Auth\SocialiteController@twitchDisconnect') }}">Disconnect?</a>
              </p>
            </div>
          @else
            <div class="content">
              <p>
                You can use your Twitch account to log in to recgam.es.
              </p>
              <p>
                Recgam.es stores your Twitch user ID and display name.
              </p>
            </div>
            <a href="{{ action('Auth\SocialiteController@twitchRedirect') }}">
              <img alt="Connect Twitch" src="{{ asset('img/twitch-login.png') }}">
            </a>
          @endif
        </div>
        <div class="column">
          <h3 class="subtitle">Steam</h3>
          @if ($user->steam_id)
            <div class="content">
              <p>
                Connected with Steam.
              </p>
              <p>
                <a href="{{ action('Auth\SocialiteController@steamDisconnect') }}">Disconnect?</a>
              </p>
            </div>
          @else
            <div class="content">
              <p>
                Connecting with Steam allows you to log in using Steam, and to
                easily view uploaded recorded games that you played in.
              </p>

              <p>
                Recgam.es stores your Steam user ID and display name.
              </p>
            </div>
            <p>
              <a href="{{ action('Auth\SocialiteController@steamRedirect') }}">
                <img alt="Connect Steam" src="{{ asset('img/steam-login.png') }}">
              </a>
            </p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
