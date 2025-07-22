@extends('layouts.app')

@section('content')
    <section class="py-5 text-center">
        <div class="container">
            <h1 class="display-4 text-danger mb-3">😓 429 Too Many Requests</h1>
            <p class="lead text-muted mb-4">
                You've made too many requests in a short period.<br>
                Please wait a moment before trying again.
            </p>
            <a href="{{ url()->previous() }}" class="btn btn-danger btn-lg">
                ← Go Back
            </a>
        </div>
    </section>
@endsection
