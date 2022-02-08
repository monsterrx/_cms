@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col-8">
            <div class="mt-5"></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-center">
                        <a href="{{ route('register') }}">
                            <img src="{{ asset('images/monster-logo.png') }}" alt="rx-logo" width="100px">
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form id="registerForm" class="needs-validation" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-md-8">
                                <input id="employee_number" type="text" class="form-control{{ $errors->has('employee_number') ? ' is-invalid' : '' }}" name="employee_number" value="{{ old('employee_number') }}" required autofocus placeholder="Employee Number">

                                @if ($errors->has('employee_number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('employee_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">

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
                                <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password_confirmation" required placeholder="Password Confirmation">

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
                            <div class="col-4">
                                <a href="#" class="btn btn-outline-dark btn-block" onclick="event.preventDefault(); document.getElementById('registerForm').submit();">
                                    Register
                                </a>
                            </div>
                            <div class="col"></div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12 text-center mt-0 mb-0 text-info">
                            v{{ env('APP_VERSION') }}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('login') }}" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
@endsection
