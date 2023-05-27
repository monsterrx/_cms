@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            @include('_cms.system-views._feedbacks.error')
            @include('_cms.system-views._feedbacks.success')
            <div class="card">
                <div class="card-body">
                    <div class="display-4">
                        Slider Place: #{{ $data['slider']->number }}
                    </div>
                    <span class="text-muted">Slider Arrangement Cannot be Edited.</span>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <img src="{{ url('images/headers/'.$data['slider']->image) }}" class="img-fluid" alt="{{ $data['slider']->image }}">
                        </div>
                        <div class="col-12">
                            <p class="lead">Slider Title: {{ $data['slider']->title }}</p>
                            <hr class="my-4">
                            <p class="lead">Slider Sub Title: {{ $data['slider']->sub_title }}</p>
                            <hr class="my-4">
                        </div>
                        <div class="col-6">
                            <p class="lead">Date Added: {{ date('F d, Y', strtotime($data['slider']->created_at)) }}</p>
                        </div>
                        <div class="col-6">
                            <p class="lead">Date Updated: {{ date('F d, Y', strtotime($data['slider']->updated_at)) }}</p>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="row mb-3">
                        <div class="col-4">
                            <a href="{{ route('sliders.index') }}" class="btn btn-outline-dark btn-block">Back</a>
                        </div>
                        <div class="col-4">
                            <a href="#updateSlider" class="btn btn-outline-dark btn-block" data-toggle="modal">Update</a>
                        </div>
                        <div class="col-4">
                            <a href="#deleteSlider" class="btn btn-outline-dark btn-block" data-toggle="modal">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="updateSlider" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">Edit {{ $data['slider']->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sliders.update', $data['slider']->id) }}" enctype="multipart/form-data" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label for="image" class="custom-file-label">Recommended 1351x500 Image Size</label>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-info"></i>
                                        </div>
                                    </div>
                                    <input type="text" id="title" class="form-control" name="title" placeholder="Slider Title" value="{{ $data['slider']->title }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-font"></i>
                                        </div>
                                    </div>
                                    <input type="text" id="sub_title" class="form-control" name="sub_title" placeholder="Slider Subtitle" value="{{ $data['slider']->sub_title }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="url" class="label">Link</label>
                                    <input type="text" id="link" name="link" class="form-control" placeholder="If you want a redirecting page on a slider" value="{{ $data['slider']->link }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark"><i class="fa fa-edit"></i>&nbsp;&nbsp;Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div id="deleteSlider" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">Delete {{ $data['slider']->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('sliders.destroy', [$data['slider']->id]) }}" method="post">
                    <div class="modal-body">
                        <p class="lead text-danger">Are you sure you want to delete {{ $data['slider']->title }}?</p>
                        @csrf
                        @method('DELETE')
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
