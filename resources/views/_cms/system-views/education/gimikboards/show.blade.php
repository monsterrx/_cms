@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Gimikboards
            </div>
            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>
            <div class="row my-4">
                <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                    <a href="{{ route('gimikboards.index') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>  Back</a>
                    <a href="#updateModal" class="btn btn-outline-dark float-right" data-toggle="modal"><i class="fa fa-file-import"></i>  Update</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="h3">School</h3>
                            <p class="lead">{{ $gimikboard->School->name }}</p>
                        </div>
                        @if($gimikboard->end_date === '0000-00-00')
                            <div class="col-md-8">
                                <h3 class="h3">Duration</h3>
                                <p class="lead">One Day Event</p>
                            </div>
                        @else
                            <div class="col-md-4">
                                <h3 class="h3">Start</h3>
                                <p class="lead">{{ date('F d, Y', strtotime($gimikboard->start_date)) }}</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="h3">End</h3>
                                <p class="lead">
                                    {{ date('F d, Y', strtotime($gimikboard->end_date)) }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Description</h3>
                            <span class="text-center">
                        {!! $gimikboard->description !!}
                    </span>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h3 class="h3">Date Added</h3>
                            <p class="lead">{{ date('F d, Y', strtotime($gimikboard->created_at)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h3">Last Update</h3>
                            <p class="lead">{{ date('F d, Y', strtotime($gimikboard->updated_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="updateModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ $gimikboard->title }}</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('gimikboards.update', $gimikboard->id) }}" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title" class="label">Gimik Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Gimik Title" value="{{ $gimikboard->title }}">
                                </div>
                                <div class="form-group">
                                    <label for="school_id" class="label">School</label>
                                    <select id="school_id" name="school_id" class="custom-select">
                                        <option value="{{ $gimikboard->School->id }}">{{ $gimikboard->School->name }}</option>
                                        @foreach($school as $schools)
                                            <option value="{{ $schools->id }}">{{ $schools->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sub_description" class="label">Header Content</label>
                                    <textarea type="text" class="form-control" id="sub_description" name="sub_description" placeholder="Header Content" style="height: 165px;">{{ $gimikboard->sub_description }}
                                    </textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $gimikboard->start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="label">End Date</label>
                                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $gimikboard->end_date }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="form-group">
                                            <textarea id="content" name="description">{!! $gimikboard->description !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <b class="fa fa-file-alt"></b>
                                                </div>
                                            </div>
                                            <div class="custom-file">
                                                <label class="custom-file-label">School Gimik Board Image</label>
                                                <input type="file" id="image" name="image" class="custom-file-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group fa-pull-right">
                                            <button type="submit" class="btn btn-outline-dark"><i class="fa fa-save"></i>  Save</button>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#delete-modal" data-dismiss="modal"><i class="fa fa-trash"></i>   Delete</button>
                                        </div>
                                    </div>
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

    <!-- Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog"
         aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Gimikboard</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center lead">Are you sure to delete this Gimikboard?</p>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('gimikboards.destroy', $gimikboard->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
