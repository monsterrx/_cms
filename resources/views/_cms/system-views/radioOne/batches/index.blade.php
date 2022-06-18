@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Student Jock Batches
            </div>

            <div class="row">
                <div class="col-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>

            <div class="row my-4">
                <div class="col-12">
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
                                    <th>Batch Number</th>
                                    <th>School Year</th>
                                    <th>Number of Jocks</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($batches as $batch)
                                    <?php try { ?>
                                    <tr data-href="{{ route('radioOne.batch', $batch->id) }}" onclick="viewData()">
                                        <td>{{ $batch->batch_number }}</td>
                                        <td>{{ $batch->start_year }} &mdash; {{ $batch->end_year }}</td>
                                        <td>{{ $batch->Student->count() }}</td>
                                        <td></td>
                                    </tr>
                                    <?php } catch (ErrorException $e) { ?>
                                    <tr>
                                        <td colspan="3" title="{{ $batch->id }}">
                                            <div class="text-center text-danger lead">
                                                Deleted Data
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="newBatch" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="lead">Add Student Jock Batch</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('radioOne.batches.new') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="batchNumber" class="lead">Batch Number</label>
                                    <input type="text" id="batchNumber" name="batch_number" class="form-control" placeholder="Batch Number">
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <label for="schoolYear" class="lead">School Year</label>
                        <div id="schoolYear" class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="startYear" class="lead">Start</label>
                                    <input type="number" id="startYear" name="start_year" class="form-control" placeholder="20xx">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endYear" class="lead">End</label>
                                    <input type="number" id="endYear" name="end_year" class="form-control" placeholder="20xx">
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fas fa-save"></i>  Save</button>
                            </div>
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
