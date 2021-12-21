@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Podcasts
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#new-podcast" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Podcast</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="podcastTable">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Show</th>
                                    <th>Episode</th>
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

    <div id="new-podcast" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">New Podcast</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="podcastForm" role="form" method="POST" action="{{ route('podcasts.store') }}" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="show_id" class="label">Show</label>
                                    <select id="show_id" name="show_id" class="custom-select{{ $errors->has('show_id') ? ' is-invalid': '' }}">
                                        <option value>--</option>
                                    </select>

                                    @if($errors->has('show_id'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('show_id') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="episode" class="label">Episode Name</label>
                                    <input type="text" id="episode" name="episode" class="form-control{{ $errors->has('episode') ? ' is-invalid': '' }}" value="{{ old('episode') }}" placeholder="Episode Name">

                                    @if($errors->has('episode'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('episode') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="date" class="label">Date</label>
                                    <input type="date" id="date" name="date" class="form-control{{ $errors->has('date') ? ' is-invalid': '' }}" value="{{ old('date') }}">

                                    @if($errors->has('date'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="link" class="label">URL Link</label>
                                    <input type="text" id="link" name="link" class="form-control{{ $errors->has('link') ? ' is-invalid': '' }}" value="{{ old('link') }}" placeholder="Podcast URL">

                                    @if($errors->has('link'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('link') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="image" name="image" class="custom-file-input">
                                                <label id="image" class="custom-file-label">Podcast Image</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 text-center">
                                <button type="submit" class="btn btn-outline-dark"><span class="fa fa-save"></span>  Save</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_podcast_modal" tabindex="-1" role="dialog" aria-labelledby="update_podcast_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="update_podcast_title_header" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-4 mb-4">
                            <img id="update_podcast_image" src="" alt="" class="img-fluid img-thumbnail">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <audio id="update_podcast_audio" src="" controls controlsList="nodownload" type="audio/mpeg"></audio>
                        </div>
                    </div>
                    <form id="update_podcast_form" role="form" method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_show_id" class="label">Show</label>
                                    <select id="update_show_id" name="show_id" class="custom-select{{ $errors->has('show_id') ? ' is-invalid': '' }}">
                                        <option value>--</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="update_episode" class="label">Episode Name</label>
                                    <input type="text" id="update_episode" name="episode" class="form-control" placeholder="Episode Name">
                                </div>
                                <div class="form-group">
                                    <label for="update_date" class="label">Date</label>
                                    <input type="date" id="update_date" name="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update_link" class="label">URL Link</label>
                                    <input type="text" id="update_link" name="link" class="form-control" placeholder="Podcast URL">
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="update_image" name="image" class="custom-file-input">
                                                <label id="update_image" class="custom-file-label">Podcast Image</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8 text-center">
                                <button type="submit" class="btn btn-outline-dark"><span class="fa fa-save"></span>  Save</button>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete_podcast_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Podcast Episode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete_podcast_form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete_podcast_form_text" class="text-center">
                            Undefined
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
