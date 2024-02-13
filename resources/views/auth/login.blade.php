@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-md-6">
            <div class="mt-5"></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-center">
                        <a href="{{ route('home') }}"><img src="{{ asset('images/monster-logo.png') }}" alt="rx-logo" width="100px"></a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="loginForm" class="needs-validation" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <div class="col">
                                <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <input id="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col text-center">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-6 text-center">
                                <a href="#" class="btn btn-outline-secondary btn-block" onclick="event.preventDefault(); document.getElementById('loginForm').submit();">
                                    Login
                                </a>
                            </div>

                            <div class="col-6 text-center">
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-block">Register</a>
                            </div>

                            <div class="col-12 text-center mt-3 mb-3">
                                <a id="forgotPasswordLink" class="text-primary" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>

                            <div class="col-12 text-center mt-0 mb-0 text-info">
                                v{{ env('APP_VERSION') }}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
@endsection
