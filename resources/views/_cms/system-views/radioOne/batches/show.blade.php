@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Radio One Batch '{{ $batch->batch_number }}
            </div>
            <div class="row">
                <div class="col-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>
            <div class="row">
                <div class="col-12 my-4">
                    <div class="btn-group">
                        <a href="{{ route('radioOne.batches') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>  Back</a>
                        <a href="#update-batch" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-file-import"></i>  Update</a>
                        <a href="{{ route('radioOne.batches.delete', $batch->id) }}" class="btn btn-outline-dark"
                           onclick="event.preventDefault(); document.getElementById('deleteForm').submit();"><i class="fas fa-trash-alt"></i>  Delete</a>
                    </div>
                    <form id="deleteForm" method="post" action="{{ route('radioOne.batches.delete', $batch->id) }}" hidden>
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                    <div class="btn-group fa-pull-right">
                        <a href="#addStudent" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Student</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center h3">HEADS</div>
                    <div class="row">
                        @forelse($batch->Student as $studentJocks)
                            @if($studentJocks->position === 1)
                                <div class="col-md-6">
                                    <div class="card mx-2 my-2">
                                        <div class="card-header">
                                            <a href="#removeStudent" remove_student modal route="{{ route('radioOne.remove.student', $studentJocks->id) }}" student_jock_id="{{ $studentJocks->id }}" class="close" data-toggle="modal">&times;</a>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <img src="{{ $studentJocks['image'] }}" width="100px" alt="{{ $studentJocks->image }}"/>
                                            <div class="card-body">
                                                <div class="card-title text-muted mb-0">{{ $studentJocks->first_name }} {{ $studentJocks->last_name }}</div>
                                                <div class="card-text">{{ $studentJocks->School->name }}</div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="fa-pull-right">
                                                <a href="#editStudent" add_student modal student_jock_id="{{ $studentJocks->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-edit"></i>  Edit Student</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ asset('images/default.png') }}" class="card-img" width="100px" alt="no_pic.png">
                                    <div class="card-body">
                                        <div class="card-title text-muted mb-0">Student Name</div>
                                        <div class="card-text">School</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center h3">SENIORS</div>
                    <div class="row">
                        @forelse($batch->Student as $studentJocks)
                            @if($studentJocks->position === 2)
                                <div class="col-md-6">
                                    <div class="card mx-2 my-2">
                                        <div class="card-header">
                                            <a href="#removeStudent" remove_student modal route="{{ route('radioOne.remove.student', $studentJocks->id) }}" batch_id="{{ $batch->id }}" student_jock_id="{{ $studentJocks->id }}" class="close" data-toggle="modal">&times;</a>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <img src="{{ $studentJocks['image'] }}" width="100px" alt="{{ $studentJocks->image }}"/>
                                            <div class="card-body">
                                                <div class="card-title text-muted mb-0">{{ $studentJocks->first_name }} {{ $studentJocks->last_name }}</div>
                                                <div class="card-text">{{ $studentJocks->School->school_name }}</div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="fa-pull-right">
                                                <a href="#editStudent" add_student modal student_jock_id="{{ $studentJocks->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-edit"></i>  Edit Student</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ asset('images/default.png') }}" class="card-img" width="100px" alt="no_pic.png">
                                    <div class="card-body">
                                        <div class="card-title text-muted">Student Name</div>
                                        <div class="card-text">School</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center h3">JUNIORS</div>
                    <div class="row">
                        @forelse($batch->Student as $studentJocks)
                            @if($studentJocks->position === 3)
                                <div class="col-md-6">
                                    <div class="card mx-2 my-2">
                                        <div class="card-header">
                                            <a href="#removeStudent" remove_student modal route="{{ route('radioOne.remove.student', $studentJocks->id) }}" batch_id="{{ $batch->id }}" student_jock_id="{{ $studentJocks->id }}" class="close" data-toggle="modal">&times;</a>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <img src="{{ $studentJocks['image'] }}" width="100px" alt="{{ $studentJocks->image }}"/>
                                            <div class="card-body">
                                                <div class="card-title text-muted mb-0">{{ $studentJocks->first_name }} {{ $studentJocks->last_name }}</div>
                                                <div class="card-text">{{ $studentJocks->School->school_name }}</div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="fa-pull-right">
                                                <a href="#editStudent" add_student modal student_jock_id="{{ $studentJocks->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-edit"></i>  Edit Student</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ asset('images/default.png') }}" class="card-img" width="100px" alt="no_pic.png">
                                    <div class="card-body">
                                        <div class="card-title text-muted">Student Name</div>
                                        <div class="card-text">School</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center h3">BABIES</div>
                    <div class="row">
                        @forelse($batch->Student as $studentJocks)
                            @if($studentJocks->position === 4)
                                <div class="col-md-6">
                                    <div class="card mx-2 my-2">
                                        <div class="card-header">
                                            <a href="#removeStudent" remove_student modal route="{{ route('radioOne.remove.student', $studentJocks->id) }}" batch_id="{{ $batch->id }}" student_jock_id="{{ $studentJocks->id }}" class="close" data-toggle="modal">&times;</a>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <img src="{{ $studentJocks['image'] }}" width="100px" alt="{{ $studentJocks->image }}"/>
                                            <div class="card-body">
                                                <div class="card-title text-muted mb-0">{{ $studentJocks->first_name }} {{ $studentJocks->last_name }}</div>
                                                <div class="card-text">{{ $studentJocks->School->school_name }}</div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="fa-pull-right">
                                                <a href="#editStudent" add_student modal student_jock_id="{{ $studentJocks->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-edit"></i>  Edit Student</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ asset('images/default.png') }}" class="card-img" width="100px" alt="no_pic.png">
                                    <div class="card-body">
                                        <div class="card-title text-muted">Student Name</div>
                                        <div class="card-text">School</div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="update-batch" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">Radio1 Batch '{{ $batch->batch_number }}</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('radioOne.batches.update', $batch->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="batch_number" class="lead">Batch Number</label>
                                    <input type="text" id="batch_number" name="batch_number" class="form-control" placeholder="Batch Number" value="{{ $batch->batch_number }}">
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <label for="schoolYear" class="lead">School Year</label>
                        <div id="schoolYear" class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_year" class="lead">Start</label>
                                    <input type="number" id="start_year" name="start_year" class="form-control" value="{{ $batch->start_year }}" placeholder="20xx">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_year" class="lead">End</label>
                                    <input type="number" id="end_year" name="end_year" class="form-control" value="{{ $batch->end_year }}" placeholder="20xx">
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fas fa-save"></i>  Update</button>
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

    <div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Student Jock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('radioOne.add.student', $batch->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="student_jock">Student Jock:</label>
                            <select id="student_jock" name="student_jock_id" class="custom-select">
                                @forelse($jocks as $studentJock)
                                    <option value="{{ $studentJock->id }}">{{ $studentJock->first_name }} {{ $studentJock->last_name }}</option>
                                @empty
                                    <option value="">--</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jock_position">Position:</label>
                            <select id="jock_position" name="position" class="custom-select">
                                <option value="1">Heads</option>
                                <option value="2">Seniors</option>
                                <option value="3">Juniors</option>
                                <option value="4">Babies</option>
                            </select>
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

    <div class="modal fade" id="editStudent" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit-student-jock-title" class="modal-title">Undefined</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="edit-student-jock-body">
                    Undefined
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeStudent" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="remove-student-jock-title" class="modal-title">Undefined</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="remove-student-jock-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="remove_student_jock_id" name="remove_student_jock_id">
                    <div id="remove-student-jock-body" class="modal-body text-center">
                        Body
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
