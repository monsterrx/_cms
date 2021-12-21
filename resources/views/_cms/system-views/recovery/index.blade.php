@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Data Recovery
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" id="active" class="tab nav-link">Award</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="tab nav-link">Employee</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Music</a>
                    <div class="dropdown-menu">
                        <a href="#" class="tab dropdown-item">Artist</a>
                        <a href="#" class="tab dropdown-item">Album</a>
                        <a href="#" class="tab dropdown-item">Song</a>
                        <a href="#" class="tab dropdown-item">Genre</a>
                    </div>
                <li class="nav-item">
                    <a href="#" class="tab nav-link">Show</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="tab nav-link">School</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="tab nav-link">Student</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="tab nav-link">Batch</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="tab nav-link">Sponsor</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Radio One</a>
                    <div class="dropdown-menu">
                        <a href="#" class="tab dropdown-item">Radio One Batch</a>
                        <a href="#" class="tab dropdown-item">Radio One Jock</a>
                    </div>
                </li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div id="container" class="mt-3 mb-5">
                        <div class="lead text-danger text-center">
                            Error Occurred
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
