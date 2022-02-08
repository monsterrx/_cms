@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('_cms.system-views._feedbacks.success')
        @include('_cms.system-views._feedbacks.error')
        <div class="col"></div>
        <div class="col-8">
            <div class="mt-5"></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-center">
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('images/monster-logo.png') }}" alt="rx-logo" width="100px">
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.request') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email or old('email') }}" required autofocus placeholder="Email">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required placeholder="Confirm Password">

                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-outline-dark btn-block">Reset Password</button>
                            </div>
                            <div class="col"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
@endsection
