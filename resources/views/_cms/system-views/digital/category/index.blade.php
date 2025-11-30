@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Article Categories
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#category-modal" data-action="add" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Category</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="categoriesTable">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Icon</th>
                                        <th>Date Created</th>
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

    <div id="category-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead" id="category-title">Edit Category</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row mb-4">
                            <div class="col-12">
                                <div id="icon_container" class="text-center">
                                    <img src="{{ asset('images/_assets/default.png') }}" class="rounded img-thumbnail" width="200" height="200">
                                    <img src="{{ asset('images/_assets/default.png') }}" class="rounded img-thumbnail" width="200" height="200">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="label">Name</label>
                                    <input type="text" id="name" name="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="label">Description</label>
                                    <textarea id="description" name="description" class="form-control" placeholder="No description available"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="theme">Icon Theme</label>
                                    <select name="theme" id="theme" class="custom-select">
                                        <option value>Please select</option>>
                                        <option value="light">Light Mode</option>
                                        <option value="dark">Dark Mode</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" id="image" name="image" class="custom_file_input">
                                        <label for="image" class="custom-file-label">Choose Icon</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group fa-pull-right">
                            <button type="submit" id="categoryUpdateButton" class="btn btn-outline-dark">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
