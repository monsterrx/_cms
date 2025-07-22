@extends('layouts.app')

@section('content')
    <section class="py-5 text-center">
        <div class="container">
            <h1 class="display-4 text-danger mb-3">😵 500 Internal Server Error</h1>
            <p class="lead text-muted mb-4">
                Sorry, something went wrong on our end.<br>
                Our team has been notified and is working on it.
            </p>
            <a href="{{ route('home') }}" class="btn btn-danger btn-lg">
                ← Return to Home
            </a>
        </div>
    </section>
@endsection
