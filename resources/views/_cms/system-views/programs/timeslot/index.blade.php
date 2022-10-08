@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Timeslots
            </div>
            <div class="row">
                <div class="col-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-6">
                    <span class="lead" id="timeslot-subtitle">Loading ...</span>
                </div>
                <div class="col-6">
                    <div class="btn-group float-right">
                        <a href="#new-sched" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-plus-circle"></i></a>
                        <button type="button" id="switch_timeslots" switch="jock" day class="btn btn-outline-dark"><i class="fas fa-exchange-alt"></i></button>
                    </div>
                </div>
            </div>
            <ul id="timeslot-days" class="nav nav-pills justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Monday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Tuesday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Wednesday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Thursday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Friday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Saturday</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" type="show">Sunday</a>
                </li>
            </ul>
            <br>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="tab-container" role="tabpanel" aria-labelledby="tab-container">
                    <div class="lead alert alert-warning text-center">
                        Error occurred, contact developer
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="new-sched" class="modal fade" role="dialog" aria-labelledby="newSched" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="lead">Add Show Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="new-timeslot" method="post" action="{{ route('timeslots.store') }}">
                        @csrf
                        {{-- Commented, you may now enter the jocks from the table itself --}}
                        {{--<div class="form-group">
                            <label class="label" for="schedule_type">Type</label>
                            <select id="schedule_type" class="custom-select">
                                <option value selected>--</option>
                                <option value="jock">Jock</option>
                                <option value="show">Show</option>
                            </select>
                        </div>--}}
                        <div id="shows" class="form-group">
                            <label class="label" for="show_id">Shows</label>
                            <select id="show_id" name="show_id" required="required" class="custom-select">
                                <option value selected>--</option>
                                @if($station === 'mnl')
                                    @foreach($shows as $show)
                                        <option value="{{ $show->id }}">{{ $show->title }} @if($show->location === $station) @elseif($show->location === 'cbu') (Cebu) @elseif($show->location === 'dav') (Davao) @endif</option>
                                    @endforeach
                                @elseif ($station === 'cbu')
                                    @foreach($shows as $show)
                                        <option value="{{ $show->id }}">{{ $show->title }} @if($show->location === $station) @elseif($show->location === 'mnl') (Manila) @elseif($show->location === 'dav') (Davao) @endif</option>
                                    @endforeach
                                @elseif ($station === 'dav')
                                    @foreach($shows as $show)
                                        <option value="{{ $show->id }}">{{ $show->title }} @if($show->location === $station) @elseif($show->location === 'cbu') (Cebu) @elseif($show->location === 'mnl') (Manila) @endif</option>
                                    @endforeach
                                @else
                                    <option value selected>Error occurred [Station is undefined]</option>
                                @endif
                            </select>
                        </div>
                        {{--<div id="jocks" class="form-group" hidden>
                            <label class="label" for="jock_id">Jocks</label>
                            <select id="jock_id" name="jock_id" class="custom-select">
                                <option value selected>--</option>
                                @if ($station === 'mnl')
                                    @foreach($jocks as $jock)
                                        <option value="{{ $jock->id }}">{{ $jock->name }} ({{ $jock->Employee->first_name }} {{ $jock->Employee->last_name }}) @if($jock->Employee->location === $station) @elseif($jock->Employee->location === 'cbu') (Cebu) @elseif($jock->Employee->location === 'dav') (Davao) @endif</option>
                                    @endforeach
                                @elseif ($station === 'cbu')
                                    @foreach($jocks as $jock)
                                        <option value="{{ $jock->id }}">{{ $jock->name }} ({{ $jock->Employee->first_name }} {{ $jock->Employee->last_name }}) @if($jock->Employee->location === $station) @elseif($jock->Employee->location === 'mnl') (Manila) @elseif($jock->Employee->location === 'dav') (Davao) @endif</option>
                                    @endforeach
                                @elseif ($station === 'dav')
                                    @foreach($jocks as $jock)
                                        <option value="{{ $jock->id }}">{{ $jock->name }} ({{ $jock->Employee->first_name }} {{ $jock->Employee->last_name }}) @if($jock->Employee->location === $station) @elseif($jock->Employee->location === 'cbu') (Cebu) @elseif($jock->Employee->location === 'mnl') (Manila) @endif</option>
                                    @endforeach
                                @else
                                    <option value selected>Error occurred [Station is undefined]</option>
                                @endif
                            </select>
                        </div>--}}
                        <div class="form-group">
                            <label class="label" for="day">Day</label>
                            <select id="day" name="day" class="custom-select">
                                <?php ?>
                                <option value selected>--</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start" class="label">Start</label>
                                    <input type="time" id="start" name="start" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end" class="label">End</label>
                                    <input type="time" id="end" name="end" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
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

    <!-- Modal -->
    <div class="modal fade" id="edit-timeslot" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="timeslot-modal-title" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update-timeslot-form" class="form-horizontal" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="label" for="update_schedule_type">Type</label>
                            <select id="update_schedule_type" class="custom-select">
                                <option value selected>--</option>
                                <option value="jock">Jock</option>
                                <option value="show">Show</option>
                            </select>
                        </div>
                        <div id="update_shows" class="form-group">
                            <label class="label" for="update_show_id">Shows</label>
                            <select id="update_show_id" name="show_id" required="required" class="custom-select">
                                @foreach($shows as $show)
                                    <option value="{{ $show->id }}">{{ $show->id }} - {{ $show->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="update_jocks" class="form-group" hidden>
                            <label class="label" for="update_jock_id">Jocks</label>
                            <select id="update_jock_id" name="jock_id" class="custom-select">
                                @foreach($jocks as $jock)
                                    <option value="{{ $jock->id }}">{{ $jock->id }} - {{ $jock->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="day" class="label">Day</label>
                            <select id="update_day" name="day" class="custom-select">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update_start" class="label">Start</label>
                                    <input type="time" id="update_start" name="start" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update_end" class="label">End</label>
                                    <input type="time" id="update_end" name="end" class="form-control">
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <div class="fa-pull-right btn-group">
                            <button type="submit" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-timeslot" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="delete-timeslot-header" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete-timeslot-form" type="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-timeslot-body" class="h5 text-center"></div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
