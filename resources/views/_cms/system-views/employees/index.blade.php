@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Employees
            </div>

            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>

            @if(Auth()->user()->Employee->Designation->level === 1)
                <div class="row my-4">
                    <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                        @if(Auth::user()->Employee->Designation->level === 1 || Auth::user()->Employee->Designation->level === 2)
                            <a href="#create_employee_modal" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Employee</a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="employeesTable">
                                <thead>
                                <tr>
                                    <th>Employee No.</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Location</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="create_employee_modal" tabindex="-1" role="dialog" aria-labelledby="create_employee_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('employees.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="first_name" class="label">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name">
                                    <label for="last_name" class="label">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="address" class="label">Address</label>
                                                    <input type="text" id="address" name="address" class="form-control" placeholder="Address">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="contact_number" class="label">Contact</label>
                                                    <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Contact Number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="gender" class="label">Gender</label>
                                                    <select id="gender" name="gender" class="custom-select">
                                                        <option value selected>--</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="birthday" class="label">Birthday</label>
                                                    <input type="date" id="birthday" name="birthday" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="designation_id" class="label">Designation</label>
                                    <select id="designation_id" name="designation_id" class="custom-select">
                                        <option value selected>--</option>
                                        @forelse($designation as $designations)
                                            <option value="{{ $designations->id }}">{{ $designations->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="submit" id="saveBtn" class="btn btn-outline-dark">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_employee_modal" tabindex="-1" role="dialog" aria-labelledby="update_employee_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update_employee_form" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col"></div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="update_first_name" class="label">First Name</label>
                                    <input type="text" id="update_first_name" name="first_name" class="form-control" placeholder="First Name">
                                    <label for="update_last_name" class="label">Last Name</label>
                                    <input type="text" id="update_last_name" name="last_name" class="form-control" placeholder="Last Name">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="update_address" class="label">Address</label>
                                                    <input type="text" id="update_address" name="address" class="form-control" placeholder="Address">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="update_contact_number" class="label">Contact</label>
                                                    <input type="text" id="update_contact_number" name="contact_number" class="form-control" placeholder="Contact Number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="update_gender" class="label">Gender</label>
                                                    <select id="update_gender" name="gender" class="custom-select">
                                                        <option value selected>--</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="update_birthday" class="label">Birthday</label>
                                                    <input type="date" id="update_birthday" name="birthday" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="update_designation_id" class="label">Designation</label>
                                    <select id="update_designation_id" name="designation_id" class="custom-select">
                                        <option value selected>--</option>
                                        @forelse($designation as $designations)
                                            <option value="{{ $designations->id }}">{{ $designations->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col"></div>
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

    <div class="modal fade" id="delete_employee_modal" tabindex="-1" role="dialog" aria-labelledby="delete_employee_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete_employee_form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div id="delete_employee_form_body" class="modal-body">
                        Undefined
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
