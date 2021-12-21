@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                New Countdown Song
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <form action="{{ action('ChartController@store') }}" method="post">
                {{ csrf_field() }}
                <input type="text" id="song_id" name="song_id" style="display: none;">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="Positions">Chart Position</label>
                        <select id="Positions" name="Positions" class="custom-select">
                            <option value="" disabled>--</option>
                            @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="active" for="chartDates">Date Charted</label>
                        <select id="chartDates" name="dated" class="form-control">
                            <option value="">--</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="song_search">TO INPUT: CLICK THE TITLE OF THE SONGS ON TABLE</label>
                        <input type="text" id="song_search" name="songName" class="form-control" readonly>
                    </div>
                    <div class="btn-group col-md-12">
                        <button type="submit" class="btn btn-outline-dark">Save</button>
                        <a href="{{ URL::previous() }}" class="btn btn-outline-dark">Back</a>
                    </div>
                </div>
            </form>
            <br>
            <table id="songList" class="table table-hover">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Song Name</th>
                        <th>Artist</th>
                        <th>Album</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($song as $songs)
                        <tr value="{{ $songs->id }}">
                            <td>{{ $songs->Album->AlbumYear }}</td>
                            <td>{{ $songs->SongName }}</td>
                            <td>{{ $songs->Album->Artist->Name }}</td>
                            <td>{{ $songs->Album->AlbumName }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row" id="loader">
        </div>
    </div>
@endsection
