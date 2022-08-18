@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Indiegrounds
            </div>
            <h2 class="h2">Artists</h2>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#new-indie" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Indie</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover" id="indiegroundsTable">
                                        <thead>
                                        <tr>
                                            <th>Artist</th>
                                            <th>Introduction</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="3">
                                                <div class="lead text-warning text-center">
                                                    Error Occurred, contact IT - Developer
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
            </div>
        </div>
    </div>

    <div id="new-indie" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">New Indie Artist</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="newIndiegroundForm" method="POST" action="{{ route('indiegrounds.store') }}">
                        @csrf
                        <input type="hidden" id="is_new" value="true" />
                        <input type="hidden" id="indie_image" name="image" />
                        <div class="form-group">
                            <label for="new_indie_artist_id" class="lead">Artist</label>
                            <select id="new_indie_artist_id" name="artist_id" class="custom-select">
                                <option value>--</option>
                                @foreach($data['artist'] as $artists)
                                    <option value="{{ $artists->id }}">{{ $artists->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="content" class="lead">Bio</label>
                            <textarea id="content" name="introduction" class="form-control" style="height: 160px;" maxlength="255"></textarea>
                        </div>
                        <div id="indieground_image" class="form-group">
                            <label for="add-artist-image" class="label">Artist Image</label>
                            <button id="add-artist-image" type="button" class="btn btn-outline-dark btn-block" role="button"><i class="fas fa-plus"></i>  Image</button>
                        </div>
                        <div class="fa-pull-right">
                            <button id="indieground_save_button" type="submit" class="btn btn-outline-dark">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="indie-artist-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="indie-name" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateIndiegroundForm" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="update_is_new" value="false" />
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="image-container"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="indie_artist_id">Artist</label>
                                        <input type="hidden" id="artist_id" name="artist_id">
                                        <select name="artist_id" id="indie_artist_id" class="custom-select" disabled>
                                            <option value>--</option>
                                            @foreach($data['artist'] as $artists)
                                                <option value="{{ $artists->id }}">{{ $artists->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="my-3"></div>
                                        <div class="custom-file">
                                            <button id="update-artist-image-button" type="button" class="btn btn-outline-dark btn-block" role="button"><i class="fas fa-plus"></i>  Image</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-3"></div>
                            <div class="form-group">
                                <label for="update-content">Profile</label>
                                <textarea name="introduction" id="update-content"></textarea>
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

        <!-- Modal -->
        <div class="modal fade" id="delete-indie-modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="delete-indie-header" class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="deleteIndiegroundForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <input type="hidden" id="delete_artist_id" name="artist_id">
                            <div id="delete-indie-body"></div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button id="yes_button" type="submit" class="btn btn-outline-dark">Yes</button>
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
