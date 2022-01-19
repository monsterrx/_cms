@extends('layouts.main')

@section('employee.nav')
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mobileNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            @foreach($jock->Show as $shows)
                @if($shows->id === 4) {{-- The Daily Survey --}}
                    <li class="nav-item">
                        <a href="{{ route('charts.daily') }}" class="nav-link">Daily Survey Top 5</a>
                    </li>
                @endif
            @endforeach
            <li class="nav-item">
                <a class="nav-link" href="{{ route('survey.votes') }}">Hit List Votes</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->Employee->FirstName }} {{ Auth::user()->Employee->LastName }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="{{ route('jocks.profile', $jock->id) }}">Profile</a>
                    <a href="#reportBug" class="dropdown-item" data-toggle="modal">Report a Bug</a>
                    <a href="{{ route('logout') }}" id="logoutJock" class="dropdown-item">
                        Sign Out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </div>
@endsection

@section('employee.content')
    <div class="row">
        @include('_cms.system-views.employeeUI.Jocks.modals.shows')
    </div>
    <hr class="my-4">
    <div class="row">
        @include('_cms.system-views.employeeUI.Jocks.modals.facts')
        @include('_cms.system-views.employeeUI.Jocks.modals.links')
    </div>
    <hr class="my-4">
    <div class="row">
        <div class="col-md-12 mb-5">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Your Photos</h3>
                </div>
                <div class="modal-body">
                    @if($image->isNotEmpty())
                        <div class="owl-carousel">
                            @foreach($image as $images)
                                <div class="card my-3 mx-5 zoom" data-toggle="modal" data-target="#edit-photo-{{ $images->id }}">
                                    <img src="/images/images_DJ/{{ $images->file }}" width="50px" alt="{{ $images->name }}">
                                    <div class="card-body">
                                        <div class="card-text">{{ $images->name }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-secondary text-center alert mt-1">
                            <p class="h6 m-0">You can post your pictures in clicking your name then profile!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
