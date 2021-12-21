@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Graphic Artist Dashboard
            </div>
            <a href="#" class="lead text-muted">{{ Auth::user()->Employee->first_name }} {{ Auth::user()->Employee->last_name }}<br></a>
            <br>
            @include('_cms.system-views._feedbacks.error')
            @include('_cms.system-views._feedbacks.success')
            <div class="row">
                <div class="col-6">
                    <span class="lead">Front Page Header</span>
                    <br>
                    <h6 style="font-weight: 300;">Slider starts at 0</h6>
                </div>
                <div class="col-6">
                    <a href="#add-slider" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <hr class="my-4">
                            <div class="row">
                                @forelse($data['slider'] as $sliders)
                                    <div class="col-sm-12 col-md-6 col-lg-6 mb-5">
                                        <div class="col-sm-2 col-md-2 col-lg-12">
                                            <h2>{{ $sliders->number }}</h2>
                                        </div>
                                        <div class="col-sm-10 col-md-10 col-lg-10">
                                            <div class="card">
                                                <img src="{{ url('images/headers/'.$sliders->image) }}" class="card-img-top img-fluid" alt="{{ $sliders->image }}">
                                                <div class="card-body">
                                                    <div class="card-title">
                                                        <span class="lead">{{ $sliders->title }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <a href="{{ route('sliders.show', $sliders->id) }}" class="btn btn-outline-dark">Settings</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center" role="alert">
                                            <span class="lead text-muted">NO SLIDERS FOUND, YOU CAN ADD</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="add-slider" class="modal fade" role="alert">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead">Add New Slider</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sliders.store') }}" enctype="multipart/form-data" method="post">
                        @csrf
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
                                    <input type="text" id="title" class="form-control" name="title" placeholder="Slider Title">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-font"></i>
                                        </div>
                                    </div>
                                    <input type="text" id="sub_title" class="form-control" name="sub_title" placeholder="Slider Subtitle">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="link" class="label">Link</label>
                                    <input type="text" id="link" name="link" class="form-control" placeholder="If you want a redirecting page on a slider">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark"><i class="fa fa-plus"></i>&nbsp;&nbsp;Save</button>
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
@endsection
