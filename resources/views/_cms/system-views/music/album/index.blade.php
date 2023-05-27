@extends('layouts.base')

@section('content')
    <div class="container mb-5">
        <div class="display-4 mb-3">
            Albums
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('_cms.system-views._feedbacks.success')
                @include('_cms.system-views._feedbacks.error')
            </div>
        </div>
        <div class="row my-4">
            <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                <a href="#new-album" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Album</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover" id="albumsTable">
                            <thead>
                            <tr>
                                <th scope="col">Album Name</th>
                                <th scope="col">Artist</th>
                                <th scope="col">Year</th>
                                <th scope="col">Options</th>
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

    <!-- Modal -->
    <div  id="new-album" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="New Album" aria-hidden="true">
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
                                    <label for="artist_id" class="col-form-label">Artist</label>
                                    <select id="artist_id" name="artist_id" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        @forelse($artists as $artist)
                                            <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                                        @empty
                                            <option value="">No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="album_year" class="col-form-label">Year</label>
                                    <input type="number" id="album_year" name="year" class="form-control" placeholder="Year">
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
                                        @forelse($genres as $genre)
                                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="albumImage" class="col-form-label">Album Image</label>
                                <div id="albumImage" class="input-group">
                                    <div class="custom-file">
                                        <label for="image" class="custom-file-label">Recommended Ratio 1:1</label>
                                        <input type="file" id="image" name="image" class="custom-file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
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
                                        <input type="text" id="update_album_name" name="name" placeholder="Album Name" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="update_artist_id">Artist</label>
                                        <select id="update_artist_id" name="artist_id" class="custom-select" required>
                                            @forelse($artists as $artist)
                                                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
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
                                        <input type="text" id="update_album_year" name="year" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="update_album_type">Type</label>
                                        <select id="update_album_type" name="type" class="custom-select" required>
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
                        <button type="submit" class="btn btn-outline-dark">Yes</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
