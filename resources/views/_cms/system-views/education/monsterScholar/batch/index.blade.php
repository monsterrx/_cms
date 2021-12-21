@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Monster Scholar
            </div>
            <h2 class="h2">Batch</h2>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#newBatch" class="btn btn-outline-dark fa-pull-right" data-toggle="modal">New Batch</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Batch</th>
                                    <th>School Year</th>
                                    <th>Scholars</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data['batch'] as $batch)
                                    <tr>
                                        <td>{{ $batch->number }}</td>
                                        <td>{{ $batch->semester }}</td>
                                        <td>{{ date("Y", strtotime($batch->start_year)) }}&nbsp;&#45;&nbsp;{{ date("Y", strtotime($batch->end_year)) }}</td>
                                        <td>{{ $batch->Student->count() }}</td>
                                        <td>
                                            <a href="{{ route('batch.show', $batch->id) }}" class="lead"><i class="fa fa-search"></i>  </a>
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

    <div id="newBatch" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">New Batch</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('batch.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="number" class="label lead">Batch Number</label>
                                    <input type="text" id="number" name="number" class="form-control" placeholder="Batch Number">
                                </div>
                                <div class="form-group">
                                    <label for="semester" class="label lead">Semester</label>
                                    <input type="text" id="semester" name="semester" class="form-control" placeholder="Semester">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <h4 class="lead">School Year</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="label">Start</label>
                                    <input type="number" id="start_date" name="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="label">End</label>
                                    <input type="number" id="end_date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="lead">Description</label>
                                    <textarea id="description" name="description" style="height: 190px;" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="lead">Batch Image</h4>
                                <div class="custom-file">
                                    <label for="image" class="custom-file-label">Recommended Image Size is 1920x1080 or is 16:9 in ratio, 2MB is the Largest Image Size</label>
                                    <input type="file" id="image" name="image" class="custom-file-input">
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fa fa-save"></i>  Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
