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
            @foreach($show as $shows)
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
                    <a class="dropdown-item" href="{{ route('jocks.profile', $jock_id) }}">Profile</a>
                    <a href="#reportBug" class="dropdown-item" data-toggle="modal">Report a Bug</a>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign Out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
@endsection

@section('employee.content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="h3 mb-0">Monster Hit List Song Votes</div>
            <p class="lead mb-0">{{ date('F d, Y', strtotime($latestChartDate)) }}</p>
            <br>
            @if(Auth::user()->Employee->Designation->level === '8')
                <div class="row">
                    <div class="col-md-12">
                        <div class="fa-pull-right">
                            <a href="#voteLogs" id="tooltip" class="btn btn-outline-dark" data-placement="bottom" data-toggle="modal" title="Vote Logs">
                                <i class="fas fa-list-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="m-3"></div>

            <div id="monsterCharts">
                <div class="alert alert-warning h5 text-center">
                    No chart data found
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="loader"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="voteLogs" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vote Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="logsTable" class="table table-hover" data-page-length="5" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Song Name</th>
                                    <th>Employee</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tallyLogs" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tallies</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tallyLogsTable" class="table table-hover" data-page-length="5" style="width: 100%;">
                        <thead>
                        <tr>
                            <th>Tally Date</th>
                            <th>Result</th>
                            <th>Last Results</th>
                            <th>Song</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop
