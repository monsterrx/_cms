@extends('layouts.app')

@section('content')
    <section class="py-5 text-center">
        <div class="container">
            <img src="{{ asset('images/_assets/404.png') }}" 
                 alt="404 Not Found Illustration" 
                 class="img-fluid mb-4" 
                 style="max-width: 300px;">
            <h1 class="display-4 text-warning mb-3">😕 404 Page Not Found</h1>
            <p class="lead text-muted mb-4">
                Sorry, the page you're looking for doesn't exist or has been moved.<br>
                You can return to the homepage or use the navigation to find what you need.
            </p>
            <a href="{{ route('home') }}" class="btn btn-info btn-lg">
                ← Go to Homepage
            </a>
        </div>
    </section>
@endsection
