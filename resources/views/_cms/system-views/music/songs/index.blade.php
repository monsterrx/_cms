@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Songs
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-8"></div>
                <div class="col-md-4 fa-pull-right btn-group">
                    <a href="#new-artist" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Artist</a>
                    <a href="#new-album" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Album</a>
                    <a href="#new-song" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Song</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="songsTable">
                                <thead>
                                <tr>
                                    <th>Song Name</th>
                                    <th>Artist</th>
                                    <th>Album</th>
                                    <th>Year</th>
                                    <th>Demo</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modals -->
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
                    <form id="songForm" action="{{ route('songs.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="song_type">Song Type</label>
                                    <select id="song_type" name="type" class="custom-select">
                                        <option value="none" selected>None</option>
                                        <option value="spotify">Spotify</option>
                                        <option value="mp3/m4a">File</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="song_name" class="label">Song</label>
                                    <input type="text" class="form-control" id="song_name" name="name" required placeholder="Song">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="spotify" class="col-md-12">
                                <div class="form-group">
                                    <label for="spotify" class="label">Track Link from Spotify</label>
                                    <input type="text" class="form-control" id="spotify_link" name="track_link" placeholder="1DFixLWuPkv3KT3TnV35m3" title="Just get the link from the end of the URL https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3" data-toggle="tooltip" data-placement="bottom">
                                </div>
                            </div>
                            <div id="file" class="col-md-12" hidden>
                                <label for="upload_song">Song File</label>
                                <div id="upload_song" class="form-group">
                                    <div class="custom-file">
                                        <input type="file" id="song_file" name="track_link" class="custom-file-input" accept="audio/*" disabled>
                                        <div class="custom-file-label">Upload Song</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="song_artist_id" class="label">Artist</label>
                                    <select id="song_artist_id" name="artist" class="custom-select">
                                        <option value="" selected disabled>--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <input type="hidden" id="album_id" name="album_id">
                                <label for="name" class="label">Album</label>
                                <input type="text" id="name" class="form-control" readonly>
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
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="new-artist" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="New Artist" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">New Artist</h3>
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="artistForm" method="POST" action="{{ route('artists.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="ArtistImage" name="image">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="artist_name" name="name" placeholder="Artist Name" required>
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
                                    <select id="country" name="country" class="custom-select" required>
                                        <option value selected>--</option>
                                        @foreach($country as $countries)
                                            <option value="{{ $countries }}">{{ $countries }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user-friends"></i>
                                        </div>
                                    </div>
                                    <select class="custom-select" id="artist_type" name="type" required>
                                        <option value="" selected>Select Type</option>
                                        <option value="Solo">Solo</option>
                                        <option value="Duo">Duo</option>
                                        <option value="Trio">Trio</option>
                                        <option value="Group">Group</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 mb-3">
                                <button id="add-artist-image" type="button" class="btn btn-outline-dark btn-block" role="button"><i class="fas fa-plus"></i>  Image</button>
                            </div>
                        </div>
                        <div class="btn-group fa-pull-right">
                            <button id="album_submit" type="submit" class="btn btn-outline-dark">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-outline-dark" data-dismiss="modal">Cancel</a>
                </div>
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
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="album_name">Album Name</label>
                                    <input type="text" class="form-control" id="album_name" name="name" placeholder="Album Name">
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
                                    <label for="year" class="col-form-label">Year</label>
                                    <input type="number" id="year" name="year" class="form-control" placeholder="Year">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="album_type" class="col-form-label">Album Type</label>
                                    <select id="album_type" name="type" class="custom-select">
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
                                        @forelse($genre as $genres)
                                            <option value="{{ $genres->id }}">{{ $genres->name }}</option>
                                        @empty
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
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
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
                <form id="updateSongForm" action method="POST" enctype="multipart/form-data">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_song_type">Song Type</label>
                                    <select id="update_song_type" name="type" class="custom-select">
                                        <option value="none" selected>None</option>
                                        <option value="spotify">Spotify</option>
                                        <option value="mp3/m4a">File</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_song_name" class="label">Song</label>
                                    <input type="text" class="form-control" id="update_song_name" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="update_spotify" class="col-md-12" hidden>
                                <div class="form-group">
                                    <label for="spotify" class="label">Track Link from Spotify</label>
                                    <input type="text" class="form-control" id="update_spotify_link" name="track_link" placeholder="1DFixLWuPkv3KT3TnV35m3" title="Just get the link from the end of the URL https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3" data-toggle="tooltip" data-placement="bottom" disabled>
                                </div>
                            </div>
                            <div id="update_file" class="col-md-12" hidden>
                                <label for="upload_song">Song File</label>
                                <div id="upload_song" class="form-group">
                                    <div class="custom-file">
                                        <input type="hidden" id="update_song_file_old" name="track_link" disabled>
                                        <input type="file" id="update_song_file" name="track_link" class="custom-file-input" accept="audio/*" disabled>
                                        <div class="custom-file-label">Upload Song</div>
                                    </div>
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
                            <input type="text" id="update_album_name" class="form-control" readonly>
                        </div>
                        <div class="my-2"></div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <table id="updateAlbumList" class="table table-hover">
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
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
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
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-artist-image" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Artist Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="artistImageForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="update_artist_id" name="artist_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 my-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="croppingImage" class="cropper img-fluid"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="artist_image_name" name="artist_image_name" style="display: none;" />
                                <div class="my-2"></div>
                                <div class="custom-file" id="custom">
                                    <input type="file" id="artist_image" name="image" class="custom-file-input" accept="image/*">
                                    <label id="artist_image" for="image" class="custom-file-label">Click here</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button id="artistCropButton" type="button" class="btn btn-outline-dark" hidden>Crop</button>
                                    <button id="saveButton" type="submit" class="btn btn-outline-dark" hidden>Save</button>
                                    <button id="cancelButton" type="button" class="btn btn-outline-dark" data-role="none" hidden>Cancel</button>
                                    <button id="doneButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">Done</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
