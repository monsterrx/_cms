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
                    <a href="#AddCategory" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Category</a>
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
                                    <th>Description</th>
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
    <div id="AddCategory" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="lead">Add New Category</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="createCategoryForm" action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="categoryName" class="label">Category Name</label>
                            <input type="text" id="categoryName" name="categoryName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description" class="label">Description</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="submit" class="btn btn-outline-dark">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @foreach($categories as $category)
        <div id="edit-{{ $category->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="lead">Edit {{ $category->name }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label for="categoryName" class="label">Category Name</label>
                                <input type="text" id="categoryName" name="categoryName" class="form-control" value="{{ $category->name }}">
                            </div>
                            <div class="form-group">
                                <label for="description" class="label">Description</label>
                                <textarea id="description" name="description" class="form-control">{{ !$category->description ? 'No Description Available' : $category->description }}</textarea>
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
    @endforeach
@endsection
