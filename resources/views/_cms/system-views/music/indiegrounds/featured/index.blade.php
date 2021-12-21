@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">Indiegrounds
            </div>
            <h2 class="h2">Featured Artist</h2>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#new-featured" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Featured Artist</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div id="featuredIndieContainer">
                        <div class="lead alert alert-danger text-center">
                            Error Occurred! Contact IT - Web Developer
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-featured" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">New Featured Indie Artist</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="featuredIndieForm" method="POST" action="{{ route('featured.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="featured_indie_artist_id" class="lead">Artist</label>
                            <select id="featured_indie_artist_id" name="independent_id" class="custom-select">
                                <option value>--</option>
                                @foreach($indieArtists as $artist)
                                    <option value="{{ $artist->id }}">{{ $artist->Artist->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="featured_content" class="form-group">
                            <label for="content" class="lead">Bio</label>
                            <textarea id="content" name="content" class="form-control" style="height: 160px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" id="date" name="date" class="form-control">
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

    <div class="modal fade" id="update-featured-indie" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="update-featured-indie-header" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateFeaturedIndieForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="update_featured_artist_id" class="lead">Artist</label>
                                    <select id="update_featured_artist_id" name="independent_id" class="custom-select">
                                        <option value>--</option>
                                        @foreach($indieArtists as $artist)
                                            <option value="{{ $artist->id }}">{{ $artist->Artist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="update_content" class="lead">Bio</label>
                                    <textarea id="update-content" name="content" class="form-control" style="height: 160px;"></textarea>
                                </div>
                            </div>
                            <div id="artist_albums_row" class="col-md-8">

                            </div>
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

    <div class="modal fade" id="delete-featured-indie" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="delete-featured-indie-header" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteFeaturedIndieForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-featured-indie-body"></div>
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
@endsection
