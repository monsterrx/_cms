@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('_cms.system-views._feedbacks.success')
        @include('_cms.system-views._feedbacks.error')
        <div class="col"></div>
        <div class="col-6">
            <div class="mt-5"></div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title text-center"><a href="{{ route('home') }}"><img src="{{ asset('images/monster-logo.png') }}" alt="rx-logo" width="100px"></a></div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}
                        <p class="text-center">Provide the registered email so we can send you the reset password link.</p>

                        <div class="form-group">
                            <div class="col-12">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col"></div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-outline-dark btn-block">
                                    Reset Password
                                </button>
                            </div>
                            <div class="col"></div>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <a href="{{ route('login') }}" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</div>
@endsection
