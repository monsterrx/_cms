<form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <img src="{{ $student->image }}" alt="{{ $student->first_name }}" class="img-fluid img-thumbnail">
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name" class="lead">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $student->first_name }}" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="last_name" class="lead">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $student->last_name }}" placeholder="Last Name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="school_id" class="lead">School</label>
                            <select id="student_school_id" name="school_id" class="custom-select">
                                <option value="{{ $student->School->id }}">{{ $student->School->name }}</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="course" class="lead">Course</label>
                            <input type="text" id="course" name="course" class="form-control" value="{{ $student->course }}" placeholder="Course">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="year_level" class="lead">Year Level</label>
                            <input type="number" id="year_level" name="year_level" class="form-control" value="{{ $student->year_level }}" placeholder="Year Level">
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
            </div>
        </div>
        <hr class="my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="update-content" class="lead">Student Data</label>
                    <textarea id="update-content" name="student_data" class="form-control">{!! $student->data !!}</textarea>
                </div>
            </div>
        </div>
        <div class="my-4"></div>
        <div class="btn-group">
            <button type="submit" class="btn btn-outline-dark"><i class="fa fa-file-import"></i>  Update</button>
            <a href="#deleteStudentModal" data-id="{{ $student->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-trash-alt"></i>  Delete</a>
        </div>
    </div>
    <div class="modal-footer">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
        </div>
    </div>
</form>
