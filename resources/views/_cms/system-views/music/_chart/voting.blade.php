@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                {{ env('STATION_CHART') }} Votes Results
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <label for="dated" class="lead">Chart Date:</label>
                        <select id="chartDates" name="dates" class="custom-select" data-chart-type="voting">
                            <option value>--</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="fa-pull-right">
                        <a href="#voteLogs" id="tooltip" class="btn btn-outline-dark" data-placement="bottom" data-toggle="modal" title="Vote Logs">
                            <i class="fas fa-list-alt"></i>
                        </a>
                        <a href="#tallyLogs" data-help="tooltip" class="btn btn-outline-dark" data-placement="bottom" data-toggle="modal" title="Tally Logs">
                            <i class="fas fa-chart-bar"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="my-5"></div>

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
                                    <table id="logsTable" class="table table-hover" style="width: 100%" data-page-length="5">
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
                            <table id="tallyLogsTable" class="table table-hover" style="width: 100%" data-page-length="5">
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

        </div>
    </div>
@endsection
