@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Employee Designations
            </div>

            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>

            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#addDesignation" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Designation</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Designation</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($designation as $designations)
                                    <tr>
                                        <td>{{ $designations->level }}</td>
                                        <td>{{ $designations->name }}</td>
                                        <td>{{ $designations->description }}</td>
                                        <td>
                                            <a href="#edit-{{ $designations->id }}" data-toggle="modal" class="btn btn-outline-dark">Info</a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addDesignation" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title lead">New Designation</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('designations.store') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Designation Name">
                        </div>

                        <div class="form-group">
                            <label for="level" class="label">Level</label>
                            <select type="text" id="level" name="level" class="custom-select">
                                <option value selected disabled>--</option>
                                @for($i = 0; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description" class="label">Description</label>
                            <textarea id="description" name="description" class="form-control" placeholder="Designation Description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-dark">Save</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($designation as $designations)
        <div id="edit-{{ $designations->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title lead">Edit Designation {{ $designations->name }}</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('designations.update', $designations->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label for="name" class="label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $designations->name }}">
                            </div>

                            <div class="form-group">
                                <label for="level" class="label">Level</label>
                                <select type="text" id="level" name="level" class="custom-select">
                                    <option value="{{ $designations->level }}" selected>{{ $designations->level }}</option>
                                    @for($i = 0; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description" class="label">Description</label>
                                <textarea id="description" name="description" class="form-control">{{ $designations->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                    <button type="button" class="btn btn-outline-dark" onclick="event.preventDefault(); document.getElementById('deleteDesignation-{{ $designations->id }}').submit(); ">Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteDesignation-{{ $designations->id }}" action="{{ route('designations.destroy', $designations->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
