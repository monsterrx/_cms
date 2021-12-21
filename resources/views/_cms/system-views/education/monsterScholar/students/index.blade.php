@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0">
            <div class="display-4 mb-3">
                Monster Scholar
            </div>
            <h2 class="h2">Students</h2>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#addStudent" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Student</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="schoolsTable" class="table table-hover" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>School</th>
                                    <th>Year Level</th>
                                    <th>Course</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center text-warning">Error Occurred, Please contact IT - Web Developer</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addStudent" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="lead modal-title">Add Student</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="newStudentForm" method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="lead">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="lead">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_id" class="lead">School</label>
                                    <select id="school_id" name="school_id" class="custom-select">
                                        <option value>--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="course" class="lead">Course</label>
                                    <input type="text" id="course" name="course" class="form-control" placeholder="Course">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year_level" class="lead">Year Level</label>
                                    <input type="number" id="year_level" name="year_level" class="form-control" placeholder="Year Level">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="custom-form" class="lead">Student Image</label>
                                    <div class="custom-file" id="custom-form">
                                        <input type="file" id="student_image" name="image" class="custom-file-input">
                                        <label for="student_image" class="custom-file-label">Choose Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content" class="lead">Student Data</label>
                                    <textarea id="content" name="student_data" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark"><i class="fa fa-save"></i>  Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="openStudentData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="student_modal_body">
                    <div class="modal-body">
                        <div class="text-center h4 text-danger">Error Loading</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteStudentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Delete Student</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ url('students') }}">
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        <input type="text" id="student_id" style="display: none;">
                        <p class="h6">Delete Student?</p>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
