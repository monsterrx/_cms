@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div id="chart-title" class="display-4">
                Southside Sounds
            </div>
            <p id="chart-subtitle" class="h4 mb-0">Local charts in Cebu</p>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="row">
                <div class="col-md-4 col-sm-12 col-lg-4">
                    <label for="dated" class="lead">Chart Date:</label>
                    <select id="chartDates" data-payload="southsides" name="dates" class="custom-select">
                        <option value disabled>--</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="fa-pull-right">
                        <div class="btn-group">
                            <button id="post" data-payload="{{ $latestChartDate }}" data-local="1" class="btn btn-outline-dark" data-toggle="tooltip" data-placement="bottom" title="Make a chart visible in the website">Post Charts</button>
                        </div>
                        <a href="#newEntry" class="btn btn-outline-dark" data-toggle="modal">New Entry</a>
                    </div>

                    <button id="official" data-payload="{{ $latestChartDate }}" data-local="1" class="btn btn-outline-dark" data-toggle="tooltip" data-placement="bottom" title="These are the charts that are not shown in the website">Official Chart</button>
                    <button id="draft" data-payload="{{ $latestChartDate }}" data-local="1" class="btn btn-outline-dark" data-toggle="tooltip" data-placement="bottom" title="These are the charts that are not shown in the website">Draft Chart</button>
                </div>
            </div>
            <br>

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

    <div id="newEntry" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="newEntryChartsForm" action="{{ route('charts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="local" name="local" value="1">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Positions">Chart Position</label>
                                    <select name="Positions" id="Positions" class="form-control">
                                        @if(env('STATION_CODE') === "mnl")
                                            @for($i = 1; $i <= 30; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        @elseif(env('STATION_CODE') === "dav")
                                            @for($i = 1; $i <= 30; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        @elseif(env('STATION_CODE') === "cbu")
                                            @for($i = 1; $i <= 40; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        @else
                                            <option value>--</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($latestChartDate === "" || $latestChartDate === null)
                                    <div class="form-group">
                                        <label for="dated">Chart Date</label>
                                        <input type="date" id="dated" name="dated" class="form-control">
                                    </div>
                                @else
                                    <div id="newChartDateInput" class="form-group">
                                        <label for="newEntryChartsDate">Chart Date</label>
                                        <input type="date" id="newEntryChartsDate" name="dated" class="form-control">
                                        <select id="newEntryChartDate" name="dated" class="form-control" hidden disabled>
                                            <option value="">--</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="new_song_id">Please select the song in the table</label>
                                <input type="text" id="new_song_id" name="song_id" class="form-control" style="display: none;">
                                <input type="text" id="SongName" name="SongName" readonly class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fas fa-save"></i>  Save</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="localSongsList" class="table table-hover" style="width: 100%;" data-page-length="5">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Year</th>
                                        <th>Song Name</th>
                                        <th>Artist</th>
                                        <th>Album</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="4" style="color: red">No Data Found</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-chart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Charted Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="newLocalChartedSongForm" method="POST" action="{{ route('charts.new') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="local" value="1">
                        <input type="hidden" id="song_id" name="song_id">
                        <div class="form-group">
                            <label class="lead" for="song">Song</label>
                            <input type="text" readonly id="song" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="lead" for="updatePositions">Position</label>
                            <select id="updatePositions" name="Positions" class="custom-select">
                                @if(env('STATION_CODE') === "mnl")
                                    @for($i = 1; $i <= 30; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @elseif(env('STATION_CODE') === "dav")
                                    @for($i = 1; $i <= 30; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @elseif(env('STATION_CODE') === "cbu")
                                    @for($i = 1; $i <= 40; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @else
                                    <option value>--</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="lead" for="dated">Date Charted</label>
                            <input id="dated" name="dated" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button id="modalSaveButton" type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-chart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="update-modal-title" class="modal-title">Undefined</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateLocalChartedSongForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" id="update_song_id" name="song_id">
                        <div class="form-group">
                            <label class="lead"  for="update_positions">Position</label>
                            <select id="update_positions" name="Positions" class="form-control">
                                @if(env('STATION_CODE') === "mnl")
                                    @for($i = 1; $i <= 30; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @elseif(env('STATION_CODE') === "dav")
                                    @for($i = 1; $i <= 30; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @elseif(env('STATION_CODE') === "cbu")
                                    @for($i = 1; $i <= 40; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                @else
                                    <option value>--</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="lead"  for="update_dated">Date Charted</label>
                            <input type="date" class="form-control" id="update_dated" name="dated" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <a href="#delete-chart" data-toggle="modal" class="btn btn-outline-dark" data-dismiss="modal">Delete</a>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-chart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="delete-modal-title" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteLocalChartedSongForm" method="POST" action>
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <input type="hidden" id="delete_song_id" name="song_id">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="lead text-center">
                                    Are you sure to delete this song from the charts?
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button id="deleteChartedSongButton" type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('body').on('click', 'td div a', function() {
            let chart_id = $(this).attr('data-value');
            let date = $(this).attr('data-date');
            let url = '{{ url('charts') }}';

            if (chart_id) {
                $('button[type="submit"]').attr('disabled', 'disabled');

                $.ajax({
                    url: '{{ route('charts.index') }}',
                    type: 'GET',
                    data: {
                        "chart_id": chart_id,
                    },
                    dataType: 'JSON',
                    success: (response) => {
                        $('#song, #song_id, #dated, #update_song_id, #delete_song_id, #update_dated').empty();
                        $('#song_id, #update_song_id, #delete_song_id').val(response.song.id);
                        $('#song').val(response.song.SongName);
                        $('#dated, #update_dated').val(date);
                        $('#update-modal-title').text('Update ' + response.song.SongName);
                        $('#delete-modal-title').text('Delete ' + response.song.SongName);

                        $('#updateLocalChartedSongForm, #deleteLocalChartedSongForm').attr('action', url + '/' + response.id);

                        $('button[type="submit"]').removeAttr('disabled');
                    },
                    error: (error) => {
                        Toast.fire({
                            icon: 'error',
                            title: error.status + ' ' + error.statusText
                        });
                    }
                });
            } else {
                console.log('song id not found');
            }
        });
    </script>
@endsection
