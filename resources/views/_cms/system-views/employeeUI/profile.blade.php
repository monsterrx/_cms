@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div id="page-text-profile" class="display-4">
                Your Information
            </div>
            <br>
            @include('_cms.system-views._feedbacks.success')
            @include('_cms.system-views._feedbacks.error')
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="fa-pull-left">
                        <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i>  Back</a>
                    </div>
                    <div class="btn-group float-right">
                        <a href="#passwordModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-unlock"></i>  Change Password</a>
                        <a href="#userModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-pen"></i>  Update</a>
                    </div>
                </div>
            </div>
            <div class="my-4"></div>
            <div class="row">
                <div class="col"></div>
                <div id="userInfo" class="col-md-8">
                    <div id="userProfile"></div>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>

    <div id="userModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Information</h4>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="userUpdateForm" action="{{ route('employees.update', $employee->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5 style="font-weight: 300">Employee Number: <span class="lead" style="color: red;">{{ $employee->employee_number }}</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="first_name" class="label">First Name</label>
                                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $employee->first_name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="label">Last Name</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $employee->last_name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth()->user()->Employee->Designation->level === '1' || Auth()->user()->Employee->Designation->level === '2')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="designation_id" class="label">Designation</label>
                                        <select id="designation_id" name="designation_id" class="custom-select">
                                            <option value="{{ $employee->Designation->id }}" selected>{{ $employee->Designation->name }} : Level {{ $employee->Designation->level }}</option>
                                            @forelse($designation as $designations)
                                                <option value="{{ $designations->id }}">{{ $designations->name }} : Level {{ $designations->level }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Address" class="label">Address</label>
                                    <input type="text" id="Address" name="Address" class="form-control" value="{{ $employee->address }}" placeholder="Address">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender" class="label">Gender</label>
                                    <select id="gender" name="gender" class="custom-select">
                                        @if($employee->gender === 'Male')
                                            <option value="Male" selected>Male</option>
                                            <option value="Female">Female</option>
                                        @elseif($employee->gender === 'Female')
                                            <option value="Male">Male</option>
                                            <option value="Female" selected>Female</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="birthday" class="label">birthday</label>
                                    <input type="date" id="birthday" name="birthday" class="form-control" value="{{ $employee->birthday }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_number" class="label">Contact</label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control" value="{{ $employee->contact_number }}" placeholder="Contact Number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" id="submit" class="btn btn-outline-dark">Save</button>
                            <button class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
