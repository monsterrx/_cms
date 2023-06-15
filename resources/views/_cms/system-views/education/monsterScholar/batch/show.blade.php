@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Monster Scholar Batch #{{ $batch->number }}
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="{{ route('batch.index') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>  Back</a>
                    <div class="float-right">
                        <form method="post" action="{{ route('batch.destroy', $batch->id) }}">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-outline-dark"><i class="fa fa-trash-alt"></i>  Delete</button>
                                <a href="#updateModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-file-import"></i>  Update</a>
                            </div>
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="bg-dark">
                                <img src="{{ $batch->image }}" class="img-fluid" alt="image">
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="lead">Semester</h4>
                            <p class="h4">{{ $batch->semester }}</p>
                        </div>
                        <div class="col-md-6">
                            <h4 class="lead">School Year</h4>
                            <p class="h4">{{ $batch->start_year }}&nbsp;&#45;&nbsp;{{ $batch->end_year }}</p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="lead">Description</h4>
                            @if($batch->description === '')
                                <p class="h4">No Description Available</p>
                            @else
                                <p class="h4">{{ $batch->description }}</p>
                            @endif
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="lead">Date Created</h4>
                            <p class="h4">{{ date('F m, Y', strtotime($batch->created_at)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h4 class="lead">Last Update</h4>
                            <p class="h4">{{ date('F m, Y', strtotime($batch->updated_at)) }}</p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="h4">Scholars</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="fa-pull-right">
                                        <a href="#addScholar" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-plus"></i>  Add Scholar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>School</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($batch->Student as $student)
                                    <?php try { ?>
                                    <tr>
                                        <td>{{ $student->Student->first_name }} {{ $student->Student->last_name }}</td>
                                        <td>{{ $student->Student->course }}</td>
                                        <td>{{ $student->Student->School->school_name }}</td>
                                        <td>
                                            <a href="#delete-student-id-{{ $student->Student->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php } catch (ErrorException $e) {?>

                                <?php } ?>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="h4">Sponsors</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="fa-pull-right">
                                        <a href="#addSponsor" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-plus"></i>  Add Sponsor</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>Sponsor Name</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($batch->Sponsor as $sponsor)
                                    <?php try { ?>
                                    <tr>
                                        <td>{{ $sponsor->name }}</td>
                                        <td>
                                            <a href="#delete-sponsor-id-{{ $sponsor->id }}" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php } catch (ErrorException $e) {?>
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

        </div>
    </div>

    <div id="addScholar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Scholars to this Batch</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('batch.store') }}" id="batch_post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('batch.add.param') }}?param=student"><button class="add-student btn btn-outline-dark" data-target="addScholar"><i class="fa fa-plus mr-2"></i>Student</button></a>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="row" id="studentList">
                            <input type="text" id="batch_id" name="batch_id" value="{{ $batch->id }}" style="display: none;">
                            <div class="col-md-6 col-xs-12 col-sm-12 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="student_id" class="card-title lead">Student</label>
                                            <select class="custom-select" id="student_id" name="student_id[]" required>
                                                <option value>--</option>
                                                @forelse($data['student'] as $student)
                                                    <option value="{{ $student->id }}">{{ $student->student_name }}</option>
                                                @empty
                                                    <option value>No Available Data</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="scholar_type" class="lead">Scholar Type</label>
                                            <select id="scholar_type" name="scholar_type[]" class="custom-select">
                                                <option value="0">Official</option>
                                                <option value="1">Sponsored</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark"><i class="fa fa-save"></i>  Add</button>
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

    <div id="addSponsor" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Sponsor</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('batch.store') }}" id="batch_post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('batch.add.param') }}?param=sponsor"><button class="add-sponsor btn btn-outline-dark" data-target="addSponsor"><i class="fa fa-plus mr-2"></i>Sponsor</button></a>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="row" id="sponsorList">
                            <input type="text" id="batch_id" name="batch_id" value="{{ $batch->id }}" style="display: none;">
                            <div class="col-md-6 col-xs-12 col-sm-12 col-lg-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <label for="sponsor_id" class="card-title lead">Sponsor</label>
                                        <select class="custom-select" id="sponsor_id" name="sponsor_id[]" required>
                                            <option value>--</option>
                                            @forelse($data['sponsor'] as $sponsor)
                                                <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                                            @empty
                                                <option value>No Available Data</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark"><i class="fa fa-save"></i>  Add</button>
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

    <div id="updateModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Monster Scholar Batch #{{ $batch->number }}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('batch.update', $batch->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="number" class="label lead">Batch Number</label>
                                    <input type="text" id="number" name="number" class="form-control" value="{{ $batch->number }}" placeholder="Batch Number">
                                </div>
                                <div class="form-group">
                                    <label for="semester" class="label lead">Semester</label>
                                    <input type="text" id="semester" name="semester" class="form-control" value="{{ $batch->semester }}" placeholder="Semester">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <h4 class="lead">School Year</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_year" class="label">Start</label>
                                    <input type="number" id="start_year" name="start_year" class="form-control" value="{{ $batch->start_year }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_year" class="label">End</label>
                                    <input type="number" id="end_year" name="end_year" class="form-control" value="{{ $batch->end_year }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="lead">Description</label>
                                    <textarea id="description" name="description" style="height: 190px;" class="form-control" placeholder="Description">{{ $batch->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="lead">Batch Image</h4>
                                <div class="custom-file">
                                    <label for="image" class="custom-file-label">Recommended Image Size is 1920x1080 or is 16:9 in ratio</label>
                                    <input type="file" id="image" name="image" class="custom-file-input">
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fa fa-save"></i>  Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @forelse($batch->Sponsor as $sponsor)
        <?php try { ?>
            <div class="modal fade" id="delete-sponsor-id-{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('batch.delete.param', $batch->id) }}?param=sponsor">
                                @csrf
                                <input type="text" name="id" value="{{ $sponsor->id }}" style="display: none;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="lead">
                                            Are you sure to Delete {{ $sponsor->name }} as Sponsor?
                                        </div>
                                        <div class="btn btn-group fa-pull-right">
                                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } catch (ErrorException $e) {?>
        <?php } ?>
    @empty
    @endforelse

    @forelse($batch->Student as $students)
        <div class="modal fade" id="delete-student-id-{{ $students->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('batch.delete.param', $batch->id) }}?param=student" id="delete_post2">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" name="id" value="{{ $students->id }}" style="display: none;">
                                    <div class="lead">
                                        Are you sure to Delete {{ $students->first_name }} {{ $students->last_name }} as Scholar?
                                    </div>
                                    <div class="btn btn-group fa-pull-right">
                                        <button type="submit" class="btn btn-outline-dark">Yes</button>
                                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
    @endforelse

    <script type="text/javascript">
        $(document).ready(function(){

            $('body').on('click', '.add-student,.add-sponsor', function(e){
                e.preventDefault();

                let user_parent = $(this).parent('a');
                let text = $(this).text();
                let url = user_parent.attr('href');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success: (response) => {
                        if (text === 'Student')
                        {
                            $('#studentList').append(response);

                        } else if(text === 'Sponsor'){

                            $('#sponsorList').append(response);
                        }
                    },
                    error: (error) => {
                        Toast.fire({
                            'icon': 'error',
                            'title': 'Error Occurred, refresh the page or contact IT Developer'
                        });
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endsection
