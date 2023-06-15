<!DOCTYPE html>
<!-- saved from url=(0014)about:internet -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Sean Cruz, Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name','Dashboard') }}</title>

    @include('components.links')

    @yield('stylesheet')

    @include('components.sweetalert')
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .zoom {
            transition: transform .2s;
        }

        .zoom:hover {
            cursor: pointer;
            transform: scale(1.05);
        }

        .clickable-link {
            transition: transform .1s;
        }

        .clickable-link:hover {
            color: black;
            font-weight: normal;
            cursor: pointer;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark navbar-expand-xl bg-dark">
    <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('images/monster-logo.png') }}" width="85px" alt="rx931_logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto text-center">
            @include('_cms.system-views.navigation')
            <li class="nav-item text-nowrap">
                <div class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="dropdownMenu" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->Employee->first_name }}&nbsp;</a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu">
                        <a href="{{ route('users.profile', Auth()->user()->Employee->employee_number) }}" class="dropdown-item"><i class="fas fa-user-circle"></i>&nbsp;&nbsp;Me</a>
                        <a href="#reportBug" class="dropdown-item" data-toggle="modal"><i class="fas fa-bug"></i>&nbsp;&nbsp;Report a Bug</a>
                        <a href="{{ route('messages.index') }}" class="dropdown-item"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp;Messages</a>
                        <a href="{{ route('logout') }}" id="logout" class="dropdown-item"><i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Sign Out</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <main role="main" class="col-12">
            <script src="{{ asset('js/jquery.min.js') }}"></script>
            @yield('content')
        </main>
    </div>
</div>
    <div id="dialog" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title">Just making sure</p>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="lead">Are you sure? Your changes won't be made</p>
                </div>
                <div class="modal-footer">
                    <div class="btn-group pull-left">
                        <button type="button" class="btn btn-outline-dark" onclick="window.location.reload()">Yes</button>
                        <button id="noButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reportBug" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report a Bug</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="reportBugForm" action="{{ route('report.bug') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bugTitle">Title of the Bug</label>
                                    <input type="text" id="bugTitle" name="bugTitle" class="form-control" placeholder="Title" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bug-content">Describe the steps taken before encountering the bug below</label>
                                    <textarea name="bugDescription" id="bug-content" style="height: 400px;"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="bugImage" name="bugImage" required>
                                        <div class="custom-file-label">Insert the screenshot of the bug here</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('change.password') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Current Password">
                        </div>
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="new-podcast" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">New Podcast</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="podcastForm" role="form" method="POST" action="{{ route('podcasts.store') }}" class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="show_id" class="label">Show</label>
                                    <select id="show_id" name="show_id" class="custom-select{{ $errors->has('show_id') ? ' is-invalid': '' }}">
                                        <option value>--</option>
                                    </select>

                                    @if($errors->has('show_id'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('show_id') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="episode" class="label">Episode Name</label>
                                    <input type="text" id="episode" name="episode" class="form-control{{ $errors->has('episode') ? ' is-invalid': '' }}" value="{{ old('episode') }}" placeholder="Episode Name">

                                    @if($errors->has('episode'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('episode') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="date" class="label">Date</label>
                                    <input type="date" id="date" name="date" class="form-control{{ $errors->has('date') ? ' is-invalid': '' }}" value="{{ old('date') }}">

                                    @if($errors->has('date'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="link" class="label">URL Link</label>
                                    <input type="text" id="link" name="link" class="form-control{{ $errors->has('link') ? ' is-invalid': '' }}" value="{{ old('link') }}" placeholder="Podcast URL">

                                    @if($errors->has('link'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('link') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="image" name="image" class="custom-file-input">
                                                <label id="image" class="custom-file-label">Podcast Image</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 text-center">
                                <button type="submit" class="btn btn-outline-dark"><span class="fa fa-save"></span>  Save</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newOutbreak" tabindex="-1" role="dialog" aria-labelledby="newOutbreak" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Outbreak Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="outbreakSongsForm" method="POST" action="{{ route('outbreaks.store') }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="outbreak_song_id">Song</label>
                                            <select class="custom-select" id="outbreak_song_id" name="song_id">
                                                <option value="">--</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="dated">Outbreak Date</label>
                                            <input id="dated" name="dated" type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div id="linkString" class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="lead" for="track_link">Sample Track from Spotify</label>
                                            <input type="text" id="track_link" name="track_link" class="form-control" placeholder="track:(random numbers and letters from spotify url)">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-outline-dark">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@include('components.scripts')

@yield('scripts')
</body>
</html>
