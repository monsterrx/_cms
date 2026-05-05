@extends('layouts.base')

@section('content')
<div class="container">
    <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
        <div class="display-4 mb-2">Music Awards - {{ $release->release }}</div>
        <div class="mb-3">
            <a href="{{ route('mma.view') }}" class="btn btn-outline-dark">Back to Releases</a>
            <a href="#music-award-modal"
               data-toggle="modal"
               data-action="add"
               data-release-id="{{ $release->id }}"
               class="btn btn-outline-dark fa-pull-right">
                New Award
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="musicAwardsTable" data-release-id="{{ $release->id }}">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Award Name</th>
                            <th>Type</th>
                            <th>Awardee</th>
                            <th>Featured</th>
                            <th>Image</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="music-award-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="music-award-title" class="modal-title">New Music Award</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <form id="musicAwardForm" method="POST" action="{{ route('mma-awards.store', $release->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <img
                                id="music-award-image-preview"
                                src="{{ asset('images/_assets/default.png') }}"
                                alt="Music Award Image Preview"
                                class="img-thumbnail rounded"
                                style="max-width: 220px; max-height: 220px; object-fit: cover;"
                            >
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <div id="music-award-cropper-container" class="d-none text-center">
                                <div id="musicAwardCropper"></div>
                                <div class="mt-3">
                                    <button type="button" id="cropMusicAwardButton" class="btn btn-outline-dark">Crop Image</button>
                                    <button type="button" id="cancelMusicAwardCropButton" class="btn btn-outline-dark">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="award_name">Award Name</label>
                        <input type="text" id="award_name" name="award_name" class="form-control" placeholder="Award Name">
                    </div>

                    <div class="form-group">
                        <label for="award_type">Award Type</label>
                        <select id="award_type" class="form-control">
                            <option value="">Please select</option>
                            <option value="artist">Artist</option>
                            <option value="album">Album</option>
                            <option value="song">Song</option>
                        </select>
                    </div>

                    <div class="form-group d-none" id="artist-group">
                        <label for="artist_id">Artist</label>
                        <select id="artist_id" name="artist_id" class="form-control">
                            <option value="">Please select an artist</option>
                        </select>
                    </div>

                    <div class="form-group d-none" id="album-group">
                        <label for="album_id">Album</label>
                        <select id="album_id" name="album_id" class="form-control">
                            <option value="">Please select an album</option>
                        </select>
                    </div>

                    <div class="form-group d-none" id="song-group">
                        <label for="song_id">Song</label>
                        <select id="song_id" name="song_id" class="form-control">
                            <option value="">Please select a song</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="is_featured">Featured Award</label>
                        <select id="is_featured" name="is_featured" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="music_award_image">Image</label>
                        <input type="file" id="music_award_image" name="image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="musicAwardSubmitButton" class="btn btn-outline-dark">Save</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="delete-mma" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteMusicAwardForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete Music Award</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="delete-mma-body">
                    Are you sure?
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