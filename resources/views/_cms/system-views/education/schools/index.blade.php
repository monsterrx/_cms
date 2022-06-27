@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Schools
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="#newSchool" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New School</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="genericTable">
                                <thead>
                                <tr>
                                    <th>School</th>
                                    <th>School Address</th>
                                </tr>
                                </thead>
                                @forelse($school as $schools)
                                    <tr data-toggle="modal" data-target="#school-{{ $schools->id }}">
                                        <td>{{ $schools->name }}</td>
                                        <td>{{ $schools->address }}</td>
                                    </tr>
                                @empty

                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            {{--<div class="fa-pull-right mb-5">
                {!! $school->links('pagination::bootstrap-4') !!}
            </div>--}}
        </div>
    </div>

    @forelse($school as $schools)
        <?php try { ?>
            <div id="school-{{ $schools->id }}" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="card">
                            <div class="my-4"></div>
                            <center>
                                <img src="{{ url('images/schools/'.$schools->seal) }}" width="120px" alt="{{ $schools->seal }}">
                            </center>
                            <div class="card-body">
                                <form method="POST" action="{{ route('schools.update', $schools->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group">
                                        <label for="name">School Name</label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="School Name" value="{{ $schools->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">School Address</label>
                                        <input type="text" id="address" name="address" class="form-control" placeholder="School Address" value="{{ $schools->address }}">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-file-alt"></i>
                                            </div>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" id="image" name="image" class="custom-file-input">
                                            <label for="image" class="custom-file-label">School Seal</label>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-outline-dark"><span class="fa fa-save"></span>  Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{ route('schools.destroy', $schools->id) }}" method="POST">
                                            <button type="submit" class="btn btn-outline-dark fa-pull-left" value="delete">Delete</button>
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <button type="button" class="btn btn-outline-dark fa-pull-right" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } catch (ErrorException $e) { ?>

        <?php } ?>
    @empty
    @endforelse

    <div id="newSchool" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('schools.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">School Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="School Name">
                            </div>
                            <div class="form-group">
                                <label for="address">School Address</label>
                                <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" required placeholder="School Address">
                            </div>
                            <br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-file-alt"></i>
                                    </div>
                                </div>
                                <div class="custom-file">
                                    <input type="file" id="image" name="image" class="custom-file-input" required>
                                    <label for="image" class="custom-file-label">School Seal</label>
                                </div>
                            </div>
                            <br><br>
                            <div class="col-md-12">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-outline-dark"><span class="fa fa-save"></span>  Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-outline-dark fa-pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
