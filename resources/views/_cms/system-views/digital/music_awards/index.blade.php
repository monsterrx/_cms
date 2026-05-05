@extends('layouts.base')

@section('content')
<div class="container">
    <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
        <div class="display-4 mb-3">Music Awards Releases</div>

        <div class="row">
            <div class="col-md-12">
                @include('_cms.system-views._feedbacks.success')
                @include('_cms.system-views._feedbacks.error')
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-12">
                <a href="#music-award-release-modal"
                   data-toggle="modal"
                   data-action="add"
                   class="btn btn-outline-dark fa-pull-right">
                    New Music Awards Release
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="musicAwardsReleasesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Release</th>
                            <th>Live</th>
                            <th>Banner</th>
                            <th>Winners</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="music-award-release-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="music-award-release-title" class="modal-title">New Music Awards Release</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form id="musicAwardReleaseForm"
                  method="POST"
                  action="{{ route('mma-releases.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <img
                                id="music-award-release-banner-preview"
                                src="{{ asset('images/_assets/default.png') }}"
                                class="img-thumbnail rounded"
                                style="max-width: 320px; max-height: 180px; object-fit: cover;"
                                alt="Music Awards Release Banner Preview"
                            >
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <div id="music-award-release-cropper-container" class="d-none text-center">
                                <div id="musicAwardReleaseCropper"></div>
                                <div class="mt-3">
                                    <button type="button" id="cropMusicAwardReleaseButton" class="btn btn-outline-dark">
                                        Crop Banner
                                    </button>
                                    <button type="button" id="cancelMusicAwardReleaseCropButton" class="btn btn-outline-dark">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="release_year">Release</label>
                        <input
                            type="text"
                            id="release_year"
                            name="release"
                            class="form-control"
                            placeholder="e.g. 2024"
                        >
                    </div>

                    <div class="form-group">
                        <label for="banner_image">Banner Image</label>
                        <input
                            type="file"
                            id="banner_image"
                            name="banner_image"
                            class="form-control-file"
                            accept=".jpg,.jpeg,.png,.webp"
                        >
                    </div>

                    <div class="form-group">
                        <label for="release_is_live">Is Live?</label>
                        <select id="release_is_live" name="is_live" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="submit" id="musicAwardReleaseSubmitButton" class="btn btn-outline-dark">
                            Save
                        </button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="delete-mma-release" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteMusicAwardReleaseForm" method="POST" action="">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title">Delete Music Awards Release</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="delete-mma-release-body">
                    Are you sure?
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
@endsection