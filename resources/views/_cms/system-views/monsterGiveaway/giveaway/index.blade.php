@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Monster Giveaways
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#newGiveaway" class="btn btn-outline-dark fa-pull-right" data-toggle="modal" id="new_giveaway">New Giveaway</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Event Link</th>
                                    <th>Contestants</th>
                                    <th>Activity</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($giveaways as $giveaway)
                                        <tr data-href="{{ route('giveaways.show', $giveaway->id) }}" onclick="viewData()">
                                            <td>{{ $giveaway->name }}</td>
                                            <td>
                                                @if($giveaway->type === 'movies')
                                                    Monster Movie Premiere
                                                @elseif($giveaway->type === 'concerts')
                                                    Concert Tickets
                                                @else
                                                    Undefined
                                                @endif
                                            </td>
                                            <td>{{ env('APP_WEBSITE') }}/join/giveaway/{{ $giveaway->code }}</td>
                                            <td>{{ $giveaway->Contestant->count() }}</td>
                                            <td>
                                                @if($giveaway->is_active === '0')
                                                    Inactive
                                                @elseif($giveaway->is_active === '1')
                                                    Active
                                                @else
                                                    Undefined
                                                @endif
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

    <div id="newGiveaway" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">New Giveaway</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('giveaways.store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="lead">Event Code</label>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="Event Code" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="lead">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Giveaway Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="lead">Description</label>
                                    <textarea id="content" name="description" class="form-control" placeholder="Movie/Concert Description" maxlength="255"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="type" class="lead">Category</label>
                                <select id="type" name="type" class="custom-select">
                                    <option value selected>--</option>
                                    <option value="concerts">Concert Tickets</option>
                                    <option value="movies">Monster Movie Premiere</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="age_restricted" class="lead">Age Restriction</label>
                                <select id="age_restricted" name="age_restricted" class="custom-select">
                                    <option value selected>--</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="line1" class="lead">When</label>
                                    <input type="text" id="line1" name="line1" class="form-control" placeholder="Information Line 1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="line2" class="lead">Where</label>
                                    <input type="text" id="line2" name="line2" class="form-control" placeholder="Information Line 2">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="line3" class="lead">Screening Starts</label>
                                    <input type="text" id="line3" name="line3" class="form-control" placeholder="Information Line 3">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="eventImage" class="lead">Event Poster</label>
                                <div class="custom-file" id="eventImage">
                                    <input type="file" id="image" name="image" class="custom-file-input" required>
                                    <label for="image" class="custom-file-label">Movie / Concert Poster, Ratio is 2:3</label>
                                </div>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark"><i class="fa fa-save"></i>  Save</button>
                                </div>
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
