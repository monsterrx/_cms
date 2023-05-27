@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Shows
            </div>
            <div class="row">
                <div class="col-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-6">
                    <p class="lead">Monster Shows</p>
                </div>
                @php $level = Auth::user()->Employee->Designation->level; @endphp
                @if($level === 1 || $level === 2)
                    <div class="col-6">
                        <a href="#newShow" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;New Show</a>
                    </div>
                @endif
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="showsTable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Show Name</th>
                                    <th>Front Description</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Grouping</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($show as $shows)
                                    <tr data-href="{{ route('shows.update', $shows->id) }}" onclick="viewData()">
                                        <td>{{ $shows->id }}</td>
                                        <td>{{ $shows->title }}</td>
                                        <td>{{ $shows->front_description }}</td>
                                        <td>
                                            @if($shows->location === "mnl")
                                                <div class="badge badge-primary">Manila</div>
                                            @elseif($shows->location === "cbu")
                                                <div class="badge badge-warning">Cebu</div>
                                            @else
                                                <div class="badge badge-dark">Davao</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shows->is_active)
                                                <div class="badge badge-success">Active</div>
                                            @else
                                                <div class="badge badge-danger">Inactive</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($shows->is_special)
                                                <div class="badge badge-secondary">Specials</div>
                                            @else
                                                <div class="badge badge-info">Daily</div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="alert alert-danger text-center lead" role="alert">
                                                <span>No Data Found</span>
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
    <div id="newShow" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">New Show</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('shows.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label" for="title">Show Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Show Title">
                                </div>
                                <div class="form-group">
                                    <label class="label" for="front_description">Front Description</label>
                                    <input type="text" id="front_description" name="front_description" class="form-control" placeholder="Front Description">
                                </div>
                                <div class="form-group">
                                    <label class="label" for="content">Description</label>
                                    <textarea id="content" name="description" class="form-control" style="margin-top: 0; margin-bottom: 0; height: 200px;" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="label" for="is_special">Specials</label>
                                    <select id="is_special" name="is_special" class="custom-select">
                                        <option value selected>--</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                        <option value="2">Weekend Show</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="label" for="header">Icon</label>
                                    <div id="header" class="custom-file">
                                        <label class="custom-file-label" for="icon">Show Icon</label>
                                        <input type="file" id="icon" name="icon" class="custom-file-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label" for="header">Background Image</label>
                                    <div id="header" class="custom-file">
                                        <label class="custom-file-label" for="background_image">Background photo is the show's main photo</label>
                                        <input type="file" id="background_image" name="background_image" class="custom-file-input">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label" for="header">Header Image</label>
                                    <div id="header" class="custom-file">
                                        <label class="custom-file-label" for="header_image">Header photo is the long photo of the show.</label>
                                        <input type="file" id="header_image" name="header_image" class="custom-file-input">
                                    </div>
                                </div>
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
@endsection
