@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Contestants
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
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
                                    <th>Contact Number</th>
                                    <th>Age</th>
                                    <th>Email</th>
                                    <th>City/Municipality</th>
                                    <th>Giveaways Joined</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($contestant as $contestants)
                                    <tr>
                                        <td>{{ $contestants->first_name }} {{ $contestants->last_name }}</td>
                                        <td>{{ $contestants->phone_number }}</td>
                                        <td>{{ Carbon\Carbon::parse($contestants->birthday)->age }}</td>
                                        <td>{{ $contestants->email }}</td>
                                        <td>{{ $contestants->city }}</td>
                                        <td>{{ $contestants->Contest->count() }}</td>
                                        <td>
                                            <a href="{{ route('contestants.show', $contestants->id) }}" class="btn btn-outline-dark" data-toggle="tooltip" data-placement="left" title="View {{ $contestants->firstName }}"><i class="fas fa-paper-plane"></i></a>
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

    @forelse($contestant as $contestants)
        <div id="viewUser-{{ $contestants->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="lead">View User</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('contestants.update', $contestants->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="firstName" class="lead">First Name</label>
                                        <input type="text" id="firstName" name="firstName" class="form-control" value="{{ $contestants->firstName }}" placeholder="First Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="lastName" class="lead">Last Name</label>
                                        <input type="text" id="lastName" name="lastName" class="form-control" value="{{ $contestants->lastName }}" placeholder="Last Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="phoneNumber" class="lead">Contact Number</label>
                                        <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="{{ $contestants->phoneNumber }}" placeholder="Contact Number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email" class="lead">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="{{ $contestants->email }}" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="city" class="lead">City/Municipality</label>
                                        <input type="text" id="city" name="city" class="form-control" value="{{ $contestants->city }}" placeholder="City/Municipality">
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
    @empty
    @endforelse
@endsection
