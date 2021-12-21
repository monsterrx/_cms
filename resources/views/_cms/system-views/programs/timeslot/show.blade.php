@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Show Timeslot Information
            </div>
            <div class="row">
                <div class="col-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>
        </div>
        <br>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title lead">{{ $timeslots->Show->title }}</h3>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('timeslots.update', $timeslots->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="show_id" class="label">Show</label>
                        <select id="show_id" name="show_id" class="custom-select">
                            <option value="{{ $timeslots->Show->id }}" selected>{{ $timeslots->Show->title }}</option>
                            @forelse($show as $shows)
                                <option value="{{ $shows->id }}">{{ $shows->title }}</option>
                            @empty
                                <center><p class="text-center text-danger">No shows yet, Add some!</p></center>
                            @endforelse
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="day" class="label">Day</label>
                        <select id="day" name="day" class="custom-select">
                            <option value="{{ $timeslots->day }}" selected>{{ $timeslots->day }}</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start" class="label">Start</label>
                                <input type="time" id="start" name="start" class="form-control" value="{{ $timeslots->start }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end" class="label">End</label>
                                <input type="time" id="end" name="end" class="form-control" value="{{ $timeslots->end }}">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="fa-pull-right btn-group">
                        <button type="submit" class="btn btn-outline-dark">Save</button>
                        <button type="button" id="deleteTimeslotButton" class="btn btn-outline-dark">Delete</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="{{ route('timeslots.index') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>  Back</a>
            </div>
        </div>

        <form id="deleteTimeslotForm" method="POST" action="{{ route('timeslots.destroy', $timeslots->id) }}">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection
