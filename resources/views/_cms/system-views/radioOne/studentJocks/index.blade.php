@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Student Jocks
            </div>

            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>

            @if(Auth::user()->Employee->Designation->level === 1 || Auth::user()->Employee->Designation->level === 2 || Auth::user()->Employee->Designation->level === 3 || Auth::user()->Employee->Designation->level === 6 || Auth::user()->Employee->Designation->level === 7)
                <div class="row my-4">
                    <div class="col-12">
                        <a href="#new-student" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Student Jock</a>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <table class="table table-hover" id="genericTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Nickname</th>
                            <th>School</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($studentJocks as $students)
                            <tr>
                                <td>{{ $students->first_name }} {{ $students->last_name }}</td>
                                <td>{{ $students->nickname }}</td>
                                <td>{{ $students->School->name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('radioOne.jocks.show', $students->id) }}" class="btn btn-outline-dark"><i class="fa fa-eye"></i>  View</a>
                                        <a href="#delete-{{ $students->id }}" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-trash-alt"></i>  Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="h4 alert alert-warning text-center mb-0">
                                        No Data Found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="new-student" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Student Jock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('radioOne.jocks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" id="nickname" name="nickname" class="form-control" placeholder="Nickname" data-toggle="tooltip" data-placement="bottom" title="Can be just the first name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="school_id" class="lead">School</label>
                                    <select id="school_id" name="school_id" class="custom-select">
                                        <option value selected>--</option>
                                        @forelse($school as $schools)
                                            <?php try { ?>
                                            <option value="{{ $schools->id }}">{{ $schools->name }}</option>
                                            <?php } catch (ErrorException $e) { ?>
                                            <option value="{{ $schools->id }}">--</option>
                                            <?php } ?>
                                        @empty
                                            <option value>No Schools Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="studentJockImage" class="lead">Student Jock Image</label>
                                <div class="custom-file" id="studentJockImage">
                                    <input type="file" id="image" name="image" class="custom-file-input">
                                    <label for="image" class="custom-file-label">Must be 1:1 or equal sizes as to Width and Height</label>
                                </div>
                            </div>
                        </div>
                        <div class="my-3"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($studentJocks as $students)
        <div class="modal fade" id="delete-{{ $students->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete {{ $students->first_name }} from the Student Jocks?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('radioOne.jocks.delete', $students->id) }}" method="POST" class="fa-pull-right">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <p class="lead text-center">This action is irreversible. Are you sure to proceed?</p>
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

        <div id="edit-{{ $students->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="lead">Edit {{ $students->nickname }}</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('radioOne.jocks.update', $students->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="lead text-dark">ID: <span class="text-danger">{{ $students->id }}</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-center">
                                                <img src="{{ $students->image }}" width="100px" alt="{{ $students->image }}" class="my-3"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="first_name" class="lead">First Name</label>
                                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $students->first_name }}" placeholder="{{ $students->first_name }}" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_name" class="lead">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $students->last_name }}" placeholder="{{ $students->last_name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nickname" class="lead">Nickname</label>
                                                <input type="text" id="nickname" name="nickname" class="form-control" value="{{ $students->nickname }}" placeholder="{{ $students->nickname }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="school_id" class="lead">School</label>
                                                <select id="school_id" name="school_id" class="custom-select">
                                                    <option value="{{ $students->School->id }}">{{ $students->School->name }}</option>
                                                    @forelse($school as $schools)
                                                        <?php try { ?>
                                                        <option value="{{ $schools->id }}">{{ $schools->name }}</option>
                                                        <?php } catch (ErrorException $e) { ?>

                                                        <?php } ?>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="form-image" class="lead">Student Image</label>
                                                <div class="custom-file" id="form-image">
                                                    <input type="file" id="image" name="image" class="custom-file-input">
                                                    <label class="custom-file-label" for="image">Image ratio should be 1:1 or height and width are both equal.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-outline-dark" value="submit">Save</button>
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
