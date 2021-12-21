@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Genre
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#add-genre" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Genre</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Genre</th>
                                    <th>Description</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($genres as $genre)
                                        <tr>
                                            <td>{{ $genre->name }}</td>
                                            <td>
                                                @if($genre->description === '')
                                                    No Available Description
                                                @else
                                                    {{ $genre->description }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#update-genre" id="update-genre-toggler" data-id="{{ $genre->id }}" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-info-circle"></i></a>
                                                    <a href="#delete-genre" id="delete-genre-toggler" data-id="{{ $genre->id }}" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
                                                <div class="alert alert-danger" role="alert">
                                                    <p class="lead">No Data Found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="add-genre" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Genre</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('genres.store') }}">
                    @csrf
                    <div class="modal-body">
                            <div class="form-group">
                                <label class="label" for="name">Genre</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="description" class="label">Description</label>
                                <textarea id="description" name="description" class="form-control" placeholder="Description"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-dark">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="update-genre" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Genre</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="updateGenreForm" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="label" for="update-name">Genre Name</label>
                            <input type="text" id="update-name" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="update-description" class="label">Description</label>
                            <textarea id="update-description" name="description" class="form-control">
                            </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-dark">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete-genre" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Delete Genre</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="deleteGenreForm" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <div class="modal-body">
                        <div id="delete-genre-body" class="lead h5 text-center">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-dark">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
