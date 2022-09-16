@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Gimikboards
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#newGimikboard" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Gimikboard</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                    <tr>
                                        <th>School</th>
                                        <th>Title</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($gimikboards as $gimikboard)
                                    <tr data-href="{{ route('gimikboards.update', $gimikboard->id) }}" onclick="viewData()">
                                        <td>{{ $gimikboard->School->name }}</td>
                                        <td>{{ $gimikboard->title }}</td>
                                        <td>
                                            @if(!$gimikboard->gimik_date_end || $gimikboard->end_date === "0000-00-00" || $gimikboard->end_date === null)
                                                {{ $gimikboard->start_date }}
                                            @else
                                                <strong>{{ $gimikboard->start_date }}</strong> <br> to <strong>{{ $gimikboard->end_date }}</strong>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="newGimikboard" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Gimikboard</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('gimikboards.store') }}" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="lead">Gimik Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_description" class="lead">Header Content</label>
                                    <input type="text" class="form-control" id="sub_description" name="sub_description" placeholder="Description">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id" class="lead">School</label>
                                    <select id="school_id" name="school_id" class="custom-select">
                                        <option value>--</option>
                                        @foreach($school as $schools)
                                            <option value="{{ $schools->id }}">{{ $schools->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gimik_date_start" class="lead">Start Date</label>
                                    <input type="date" id="gimik_date_start" name="start_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gimik_date_end" class="lead">End Date</label>
                                    <input type="date" id="gimik_date_end" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="form-group">
                                    <textarea id="content" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <b class="fa fa-file-alt"></b>
                                        </div>
                                    </div>
                                    <div class="custom-file">
                                        <label class="custom-file-label">School Gimik Board Image</label>
                                        <input type="file" id="image" name="image" class="custom-file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fa fa-save"></i>  Save</button>
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
