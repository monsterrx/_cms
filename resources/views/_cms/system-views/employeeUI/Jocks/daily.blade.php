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
                    {{ Auth::user()->Employee->first_name }} {{ Auth::user()->Employee->last_name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="{{ route('jocks.profile', $jock_id) }}">Profile</a>
                    <a href="#reportBug" class="dropdown-item" data-toggle="modal">Report a Bug</a>
                    <a href="{{ route('logout') }}" id="logoutJock" class="dropdown-item">
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
            <div class="h3">The Daily Survey Top 5</div>
            <br>

            <div class="m-3"></div>

            <div class="row">
                <div class="col-md-4">
                    <select name="dated" id="surveyDate" data-chart="1" class="form-control">
                        <option value>--</option>
                    </select>
                </div>
                <div class="col"></div>
            </div>

            <div class="m-3"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="btn-group fa-pull-left">
                        <a href="#dataList" data-toggle="modal" class="btn btn-outline-dark" data-help="tooltip" data-placement="bottom" title="Verify if the artist/album/song exists in our database">Songs Database</a>
                    </div>
                    <div class="btn-group fa-pull-right">
                        <a href="#newEntry" data-toggle="modal" class="btn btn-outline-dark" data-help="tooltip" data-placement="bottom" title="Create an entry for the daily survey top 5">New Entry</a>
                    </div>
                </div>
            </div>
            <br>
            <table class="table table-hover mb-5">
                <thead>
                <tr>
                    <th scope="col">Spot</th>
                    <th scope="col">Song</th>
                    <th scope="col">Artist</th>
                    <th scope="col">Album</th>
                    <th scope="col">Dated</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody id="dailyCharts">
                    <tr>
                        <td colspan="6" style="color: red;"><div class="text-center">No Data Found</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newEntry" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Entry</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="dailyChartsForm" action="{{ route('charts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="text" value="1" id="daily" name="daily" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Positions">Chart Position</label>
                                    <select name="Positions" id="Positions" class="form-control">
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="newChartDateInput" class="form-group">
                                    <label for="newEntryChartsDate">Chart Date</label>
                                    <input type="date" id="newEntryChartsDate" name="dated" class="form-control">
                                    <select id="newEntryChartDate" name="dated" class="form-control" hidden disabled>
                                        <option value="">--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="song_id">Please select the song in the table</label>
                                <input type="text" id="song_id" name="song_id" class="form-control" style="display: none;">
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
                                <table id="songsList" class="table table-hover" style="width: 100%;" data-page-length="5">
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

    <div class="modal fade" id="newChart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Chart Song Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="newDailyChartedSongForm" method="POST" action="{{ route('charts.new') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="new_song_id" name="song_id">
                                    <input type="hidden" id="daily" name="daily" value="1">
                                    <div class="form-group">
                                        <label class="lead" for="Positions">Position</label>
                                        <select id="Positions" name="Positions" class="custom-select">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="my-3"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lead" for="dated">Date Charted</label>
                                        <input id="dated" name="dated" type="date" class="form-control">
                                    </div>
                                </div>
                            </div>
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

    <div class="modal fade" id="updateChart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Daily Chart Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateDailyChartedSongForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" id="update_song_id" name="song_id">
                                    <input type="hidden" id="daily" name="daily" value="1">
                                    <div class="form-group">
                                        <label class="lead" for="update_positions">Position</label>
                                        <select id="update_positions" name="Positions" class="custom-select">
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="my-3"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="lead" for="dated">Date Charted</label>
                                        <input id="update_dated" name="dated" type="date" class="form-control">
                                    </div>
                                </div>
                            </div>
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

    <div class="modal fade" id="deleteChart" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Charted Song?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteDailyChartedSongForm" method="POST" action="{{ route('charts.daily.remove') }}">
                    <input type="hidden" id="delete_song_id" name="delete_song_id">
                    <div id="modal-body" class="modal-body">
                        Ajax not initialized.
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dataList" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recorded Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <ul class="nav nav-pills nav-fill" role="tablist">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-toggle="tab" data-name="artist">Artist</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-toggle="tab" data-name="album">Album</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-toggle="tab" data-name="song">Song</a>
                            </li>
                        </ul>
                        <div id="tabs" class="row my-2">
                            <div id="artistTbl" class="col-md-12" hidden>
                                <table class="table table-hover" id="artistsTable" style="width: 100%;" data-page-length="5">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Country</th>
                                            <th>Type</th>
                                            <th>Date Created</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="albumTbl" class="col-md-12" hidden>
                                <table class="table table-hover" id="albumsTable" style="width: 100%;" data-page-length="5">
                                    <thead>
                                        <tr>
                                            <th>Album</th>
                                            <th>Artist</th>
                                            <th>Year</th>
                                            <th>Date Created</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="songTbl" class="col-md-12" hidden>
                                <table class="table table-hover" id="songsTable" style="width: 100%;" data-page-length="5">
                                    <thead>
                                        <tr>
                                            <th>Song</th>
                                            <th>Artist</th>
                                            <th>Album</th>
                                            <th>Year</th>
                                            <th>Link Track</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="tabButtons" class="col-md-12 my-2">

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

    <div id="new-artist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Artist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="artistForm" action="{{ route('artists.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="Name" name="Name" autocomplete="off" placeholder="Artist Name">
                            <div class="invalid-feedback"></div>
                            <div class="valid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <select name="ArtistCountry" id="ArtistCountry" class="custom-select">
                                <option selected disabled value>Artist Country</option>
                                @foreach($country as $countries)
                                    <option value="{{ $countries }}">{{ $countries }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="ArtistType" id="ArtistType" class="custom-select">
                                <option value="Solo" selected>Solo</option>
                                <option value="Duo">Duo</option>
                                <option value="Trio">Trio</option>
                                <option value="Group">Group</option>
                            </select>
                        </div>
                        <div class="custom-file">
                            <label for="artistImage" class="custom-file-label">Artist Image. Tip: a ratio of 1:1 for the image.</label>
                            <input type="file" class="custom-file-input" id="image" name="image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">Close</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="new-album" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="New Album" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="albumForm" action="{{ route('albums.store') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="AlbumName">Album Name</label>
                                    <input type="text" class="form-control" id="AlbumName" name="AlbumName" placeholder="Album Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="album_artist_id" class="col-form-label">Artist</label>
                                    <select id="album_artist_id" name="artist_id" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="AlbumYear" class="col-form-label">Year</label>
                                    <input type="number" id="AlbumYear" name="AlbumYear" class="form-control" placeholder="Year">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="AlbumType" class="col-form-label">Album Type</label>
                                    <select id="AlbumType" name="AlbumType" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        <option value="Single">Single</option>
                                        <option value="EP">EP</option>
                                        <option value="Full">Full</option>
                                        <option value="Demo">Demo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="genre_id" class="col-form-label">Genre</label>
                                    <select id="genre_id" name="genre_id" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        @forelse($genres as $genre)
                                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                        @empty
                                            <option value="" disabled selected>--</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="albumImage" class="col-form-label">Album Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <label for="image" class="custom-file-label">Recommended Ratio 1:1</label>
                                        <input type="file" id="albumImage" name="image" class="custom-file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="btn-group fa-pull-right">
                            <button id="artist_submit" type="submit" class="btn btn-outline-dark">Save</button>
                            <a href="#new-artist" class="btn btn-outline-dark" data-toggle="modal">No Artist&nbsp;&nbsp;<i class="fa fa-question"></i></a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>

    <div id="new-song" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="New Song" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="songForm" action="{{ route('songs.store') }}" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="SongName" class="label">Song</label>
                                    <input type="text" class="form-control" id="SongName" name="SongName" required placeholder="Song">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="link_track" class="label">Track Link from Spotify</label>
                                    <input type="text" class="form-control" id="link_track" name="link_track" placeholder="1DFixLWuPkv3KT3TnV35m3" title="Just get the link from the end of the URL https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3" data-toggle="tooltip" data-placement="bottom">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="song_artist_id" class="label">Artist</label>
                                    <select id="song_artist_id" class="custom-select">
                                        <option value="" selected disabled>--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <input type="hidden" id="album_id" name="album_id">
                                <label for="AlbumName" class="label">Album</label>
                                <input type="text" id="Album" class="form-control" readonly>
                            </div>
                            <div class="col-md-12">
                                <div class="btn-group fa-pull-right">
                                    <button id="song_submit" type="submit" class="btn btn-outline-dark">Save</button>
                                    <a href="#new-album" class="btn btn-outline-dark" data-toggle="modal">No Album&nbsp;&nbsp;<i class="fa fa-question"></i></a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="m-4"></div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12" id="tablediv">
                            <table id="albumList" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Album Name</th>
                                    <th>Year</th>
                                    <th>Album Type</th>
                                    <th>Genre</th>
                                </tr>
                                </thead>
                                <tbody id="albumResults">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-artist" tabindex="-1" role="dialog" aria-labelledby="Update Artist" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Artist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateArtistForm" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center mb-3">
                                    <img id="artistImage" src="" alt="" class="img-thumbnail img-responsive" width="220px">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-user-alt"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" id="update_artist_name" name="Name" placeholder="Artist Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-flag"></i>
                                                </div>
                                            </div>
                                            <?php $country = array("Afghanistan", "Albania", "Algeria",
                                                "American Samoa", "Andorra", "Angola",
                                                "Anguilla", "Antarctica", "Antigua and Barbuda",
                                                "Argentina", "Armenia", "Aruba",
                                                "Australia", "Austria", "Azerbaijan",
                                                "Bahamas", "Bahrain", "Bangladesh",
                                                "Barbados", "Belarus", "Belgium",
                                                "Belize", "Benin", "Bermuda",
                                                "Bhutan", "Bolivia", "Bosnia and Herzegowina",
                                                "Botswana", "Bouvet Island", "Brazil",
                                                "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria",
                                                "Burkina Faso", "Burundi", "Cambodia",
                                                "Cameroon", "Canada", "Cape Verde",
                                                "Cayman Islands", "Central African Republic", "Chad",
                                                "Chile", "China", "Christmas Island",
                                                "Cocos (Keeling) Islands", "Colombia", "Comoros",
                                                "Congo", "Congo, the Democratic Republic of the", "Cook Islands",
                                                "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)",
                                                "Cuba", "Cyprus", "Czech Republic",
                                                "Denmark", "Djibouti", "Dominica",
                                                "Dominican Republic", "East Timor", "Ecuador",
                                                "Egypt", "El Salvador", "Equatorial Guinea",
                                                "Eritrea", "Estonia", "Ethiopia",
                                                "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji",
                                                "Finland", "France", "France Metropolitan",
                                                "French Guiana", "French Polynesia", "French Southern Territories",
                                                "Gabon", "Gambia", "Georgia",
                                                "Germany", "Ghana", "Gibraltar",
                                                "Greece", "Greenland", "Grenada",
                                                "Guadeloupe", "Guam", "Guatemala",
                                                "Guinea", "Guinea-Bissau", "Guyana",
                                                "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras",
                                                "Hong Kong", "Hungary", "Iceland",
                                                "India", "Indonesia", "Iran (Islamic Republic of)",
                                                "Iraq", "Ireland", "Israel",
                                                "Italy", "Jamaica", "Japan",
                                                "Jordan", "Kazakhstan", "Kenya",
                                                "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of",
                                                "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic",
                                                "Latvia", "Lebanon", "Lesotho",
                                                "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein",
                                                "Lithuania", "Luxembourg", "Macau",
                                                "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi",
                                                "Malaysia", "Maldives", "Mali",
                                                "Malta", "Marshall Islands", "Martinique",
                                                "Mauritania", "Mauritius", "Mayotte",
                                                "Mexico", "Micronesia, Federated States of", "Moldova, Republic of",
                                                "Monaco", "Mongolia", "Montserrat",
                                                "Morocco", "Mozambique", "Myanmar",
                                                "Namibia", "Nauru", "Nepal",
                                                "Netherlands", "Netherlands Antilles", "New Caledonia",
                                                "New Zealand", "Nicaragua", "Niger",
                                                "Nigeria", "Niue", "Norfolk Island",
                                                "Northern Mariana Islands", "Norway", "Oman",
                                                "Pakistan", "Palau", "Panama",
                                                "Papua New Guinea", "Paraguay", "Peru",
                                                "Philippines", "Pitcairn", "Poland",
                                                "Portugal", "Puerto Rico", "Qatar",
                                                "Reunion", "Romania", "Russian Federation",
                                                "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
                                                "Saint Vincent and the Grenadines", "Samoa", "San Marino",
                                                "Sao Tome and Principe", "Saudi Arabia", "Senegal",
                                                "Seychelles", "Sierra Leone", "Singapore",
                                                "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands",
                                                "Somalia", "South Africa", "South Georgia and the South Sandwich Islands",
                                                "Spain", "Sri Lanka", "St. Helena",
                                                "St. Pierre and Miquelon", "Sudan", "Suriname",
                                                "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden",
                                                "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China",
                                                "Tajikistan", "Tanzania, United Republic of", "Thailand",
                                                "Togo", "Tokelau", "Tonga",
                                                "Trinidad and Tobago", "Tunisia", "Turkey",
                                                "Turkmenistan", "Turks and Caicos Islands", "Tuvalu",
                                                "Uganda", "Ukraine", "United Arab Emirates",
                                                "United Kingdom", "United States of America", "United States Minor Outlying Islands",
                                                "Uruguay", "Uzbekistan", "Vanuatu",
                                                "Venezuela", "Vietnam", "Virgin Islands (British)",
                                                "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara",
                                                "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"); ?>
                                            <select id="update_artist_country" name="ArtistCountry" class="custom-select" required>
                                                <option value>--</option>
                                                @foreach($country as $countries)
                                                    <option value="{{ $countries }}">{{ $countries }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="my-3"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-user-friends"></i>
                                                </div>
                                            </div>
                                            <select class="custom-select" id="update_artist_type" name="ArtistType" required>
                                                <option value="Solo">Solo</option>
                                                <option value="Duo">Duo</option>
                                                <option value="Trio">Trio</option>
                                                <option value="Group">Group</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fa fa-file-alt"></i>
                                                </div>
                                            </div>
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="update_image">Choose File</label>
                                                <input type="file" id="update_image" name="image" class="custom-file-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label for="view_albums">View Albums</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-compact-disc"></i>
                                        </div>
                                    </div>
                                    <select class="custom-select" id="view_albums" required>
                                        <option value selected>--</option>
                                    </select>
                                </div>
                                <div class="my-4"></div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <img id="view_album_image" src="" alt="" class="img-thumbnail img-responsive" width="220px">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Song</th>
                                                <th>Genre</th>
                                            </tr>
                                            </thead>
                                            <tbody id="view_songs">
                                            <tr>
                                                <td colspan="2">
                                                    <div class="alert alert-danger lead text-center">
                                                        Error Occurred, Contact IT - Web Developer
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">Close</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-artist" tabindex="-1" role="dialog" aria-labelledby="Delete Artist" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Artist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteArtistModal" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-artist-body" class="h5 text-center">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">No</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-album" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateAlbumForm" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body row">
                        <div class="col-md-5">
                            <div class="text-center mb-3">
                                <img id="update_album_image" src="" alt="" class="img-thumbnail img-responsive" width="220px">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="update_album_name">Album</label>
                                        <input type="text" id="update_album_name" name="AlbumName" placeholder="AlbumName" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="update_artist_id">Artist</label>
                                        <select id="update_artist_id" name="artist_id" class="custom-select" required>
                                            @forelse($artists as $artist)
                                                <option value="{{ $artist->id }}">{{ $artist->Name }}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="update_genre_id">Genre</label>
                                        <select id="update_genre_id" name="genre_id" class="custom-select" required>
                                            @forelse($genres as $genre)
                                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                            @empty
                                                <option value="">No Data Found</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="update_album_year">Year</label>
                                        <input type="text" id="update_album_year" name="AlbumYear" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="update_album_type">Type</label>
                                        <select id="update_album_type" name="AlbumType" class="custom-select" required>
                                            <option value="Single">Single</option>
                                            <option value="EP">EP</option>
                                            <option value="Full">Full</option>
                                            <option value="Demo">Demo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" id="image" name="image" class="custom-file-input">
                                                <label class="custom-file-label" for="image">Album Cover</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Song</th>
                                    <th>Genre</th>
                                </tr>
                                </thead>
                                <tbody id="album_view_songs">
                                <tr>
                                    <td colspan="2">
                                        <div class="alert alert-danger lead text-center">
                                            Error Occurred, Contact IT - Web Developer
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">Close</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-album" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteAlbumForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-album-body">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark">Save</button>
                        <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">No</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-song" tabindex="-1" role="dialog" aria-labelledby="Update Song" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateSongForm" action method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="songDemoContainer" class="text-center">
                                    Loading ...
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update_song_name" class="label">Song</label>
                                    <input type="text" class="form-control" id="update_song_name" name="SongName">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update_link_track" class="label">Track Link from Spotify</label>
                                    <input type="text" class="form-control" id="update_link_track" name="link_track" placeholder="4jAIqgrPjKLTY9Gbez25Qb" title="Just get the link from the end of the URL without the quotes https://open.spotify.com/embed/track/&quot;link_track&quot;" data-toggle="tooltip" data-placement="bottom">
                                </div>
                            </div>
                        </div>
                        <div class="my-2"></div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="update_song_artist_id" class="label">Artist</label>
                                <select id="update_song_artist_id" name="song_artist_id" class="custom-select">
                                    <option value selected >--</option>
                                </select>
                            </div>
                        </div>
                        <div class="my-2"></div>
                        <div class="form-group">
                            <input type="hidden" id="update_album_id" name="album_id">
                            <label for="update_album_name" class="label">Album</label>
                            <input type="text" id="update_album_name" name="AlbumName" class="form-control" readonly>
                        </div>
                        <div class="my-2"></div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Album Name</th>
                                        <th>Year</th>
                                        <th>Album Type</th>
                                        <th>Genre</th>
                                    </tr>
                                    </thead>
                                    <tbody id="update_album_results">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="my-3"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">Close</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-song" tabindex="-1" role="dialog" aria-labelledby="Delete Song" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Song</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteSongForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-song-body" class="h5 text-center">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <a href="#dataList" class="btn btn-outline-dark" data-toggle="modal" data-dismiss="modal">No</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
