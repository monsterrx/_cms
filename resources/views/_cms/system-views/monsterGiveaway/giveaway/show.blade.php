@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <h3 class="h3">
                @if($giveaway->type === 'concerts')
                    Monster Concert Tickets
                @elseif($giveaway->type === 'movies')
                    Monster Movie Premiere
                @else
                    Undefined
                @endif
            </h3>
            <div class="display-4">
                {{ $giveaway->name }}
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <div class="btn-group fa-pull-right">
                        <a href="{{ route('giveaways.index') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>  Back</a>
                        <a href="#updateGiveaway" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-file-import"></i>  Update</a>
                        @if($giveaway->active === '0')
                            <a href="#activateGiveaway" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-check-circle"></i>  Activate</a>
                        @else
                            <a href="#deleteGiveaway" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-times-circle"></i>  Deactivate</a>
                        @endif
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <center>
                        <img src="{{ $giveaway->image }}" width="300px" alt="{{ $giveaway->name }}">
                    </center>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="h4">
                                Name
                            </div>
                            <p class="lead">{{ $giveaway->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="h4">
                                Category
                            </div>
                            <p class="lead">
                                @if($giveaway->type === 'movies')
                                    Monster Movie Premiere
                                @elseif($giveaway->type === 'concerts')
                                    Concert Tickets
                                @else
                                    Undefined
                                @endif
                            </p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="h4">
                                Event Link
                            </div>
                            <p class="lead">
                                http://rx931.com/join/giveaway/{{ $giveaway->code }}
                            </p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="h4">
                                Information
                            </div>
                            <center>
                                <p class="lead">{{ $giveaway->line1 }}</p>
                                <p class="lead">{{ $giveaway->line2 }}</p>
                                <p class="lead">{{ $giveaway->line3 }}</p>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="h4">
                        Description
                    </div>
                    <p class="lead">{!! $giveaway->description !!}</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="h4 text-center">
                        Contestants
                    </div>
                    <table id="genericTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Age</th>
                                <th>City/Municipality</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($giveaway->Contestant as $contestants)
                                <tr data-href="{{ route('contestants.show', $contestants->id) }}" onclick="viewData()">
                                    <td> {{ $contestants->first_name }} {{ $contestants->last_name }}</td>
                                    <td> {{ $contestants->phone_number }}</td>
                                    <td> {{ Carbon\Carbon::parse($contestants->birthday)->age }}</td>
                                    <td> {{ $contestants->city }}</td>
                                    <td> {{ $contestants->email }}</td>
                                </tr>
                                <?php try { ?>

                                <?php } catch (ErrorException $e) { ?>
                                    <td colspan="5">
                                        Deleted Data
                                    </td>
                                <?php } ?>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>

    <div id="updateGiveaway" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">Update
                        @if($giveaway->type === 'concerts')
                            Concert Tickets
                        @elseif($giveaway->type === 'movies')
                            Monster Movie Premiere
                        @else
                            Undefined
                        @endif</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('giveaways.update', $giveaway->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="code" class="lead">Event Code</label>
                                    <input type="text" id="code" name="code" class="form-control" value="{{ $giveaway->code }}" placeholder="Event Code" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="lead">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $giveaway->name }}" placeholder="Giveaway Name">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="lead">Description</label>
                                    <textarea id="content" name="description" class="form-control" style="height: 120px;">{!! $giveaway->description !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="type" class="lead">Category</label>
                                <select id="type" name="type" class="custom-select">
                                    @if($giveaway->type === 'concerts')
                                        <option value="concerts" selected>Concert Tickets</option>
                                        <option value="movies">Monster Movie Premiere</option>
                                    @elseif($giveaway->type === 'movies')
                                        <option value="movies" selected>Monster Movie Premiere</option>
                                        <option value="concerts">Concert Tickets</option>
                                    @else
                                        <option value selected>--</option>
                                        <option value="concerts">Concert Tickets</option>
                                        <option value="movies">Monster Movie Premiere</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="active" class="lead">Activity</label>
                                <select id="active" name="active" class="custom-select">
                                    @if($giveaway->active === '1')
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    @elseif($giveaway->active === '0')
                                        <option value="0" selected>Inactive</option>
                                        <option value="1">Active</option>
                                    @else
                                        <option value selected>--</option>
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="is_restricted" class="lead">Age Restriction</label>
                                <select id="is_restricted" name="is_restricted" class="custom-select">
                                    @if($giveaway->is_restricted === '1')
                                        <option value="1" selected>Yes</option>
                                        <option value="0">No</option>
                                    @elseif($giveaway->is_restricted === '0')
                                        <option value="0" selected>No</option>
                                        <option value="1">Yes</option>
                                    @else
                                        <option value selected>--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="line1" class="lead">When</label>
                                    <input type="text" id="line1" name="line1" class="form-control" value="{{ $giveaway->line1 }}" placeholder="Information Line 1">
                                </div>
                                <div class="form-group">
                                    <label for="line2" class="lead">Where</label>
                                    <input type="text" id="line2" name="line2" class="form-control" value="{{ $giveaway->line2 }}" placeholder="Information Line 2">
                                </div>
                                <div class="form-group">
                                    <label for="line3" class="lead">Screening Starts</label>
                                    <input type="text" id="line3" name="line3" class="form-control" value="{{ $giveaway->line3 }}" placeholder="Information Line 3">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="image" class="lead">Event Poster</label>
                                <div class="custom-file" id="image">
                                    <input type="file" id="image" name="image" class="custom-file-input">
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

    <div id="activateGiveaway" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">Activate {{ $giveaway->name }}</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <center>
                        <form method="GET" action="{{ route('giveaways.activate', $giveaway->id) }}">
                            @csrf
                            <div class="mt-5 mb-5">
                                <h3 class="lead">Activate {{ $giveaway->name }}?</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="active" name="active" value="1" hidden>
                                        <button type="submit" class="btn btn-outline-dark btn-block">Yes</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-outline-dark btn-block" data-dismiss="modal">No</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteGiveaway" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">Deactivate {{ $giveaway->name }}</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <center>
                        <form method="POST" action="{{ route('giveaways.destroy', $giveaway->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="mt-5 mb-5">
                                <h3 class="lead">Are you sure to deactivate {{ $giveaway->name }}?</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" id="active" name="active" value="0" hidden>
                                        <button type="submit" class="btn btn-outline-dark btn-block">Yes</button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="btn btn-outline-dark btn-block" data-dismiss="modal">No</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
