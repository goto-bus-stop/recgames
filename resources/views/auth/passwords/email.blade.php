@extends('layouts.main')

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

          <form role="form" method="POST" action="{{ route('password.email') }}">
              {{ csrf_field() }}

            <div class="control is-horizontal {{ $errors->has('email') ? ' has-error' : '' }}">
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
                    <div class="help is-danger">
                      {{ $errors->first('email') }}
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="control is-horizontal">
              <div class="control-label"></div>
              <div class="control">
                <button type="submit" class="button is-primary">
                  Send Password Reset Link
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
