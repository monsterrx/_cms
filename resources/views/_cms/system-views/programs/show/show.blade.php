@extends('layouts.base')

@section('content')
    <div class="container mb-5">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-2">
            <div class="display-4 mb-3">
                {{ $show->title }}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('_cms.system-views._feedbacks.error')
                @include('_cms.system-views._feedbacks.success')
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="my-2">
                    <p class="lead m-0">Main:</p>
                    <div class="text-center">
                        <img src="{{ url('images/shows/'.$show->background_image) }}" alt="{{ $show->background_image }}" class="img-fluid img-thumbnail">
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="my-2">
                    <p class="lead m-0">Header:</p>
                    <img src="{{ url('images/shows/'.$show->header_image) }}" alt="{{ $show->header_image }}" class="img-fluid img-thumbnail">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-block d-md-none d-lg-none d-xl-none">
                    <div class="row mb-3">
                        <div class="col-2">
                            <a href="{{ route('shows.index') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i></a>
                        </div>
                        <div class="col-10">
                            <div class="btn-group float-right">
                                <a href="#editModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-edit"></i></a>
                                <a href="#uploadBgModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-image"></i></a>
                                <a href="#uploadHeaderModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-photo-video"></i></a>
                                <a href="#deleteModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-block d-lg-block d-xl-block">
                    <div class="row mb-3">
                        <div class="col-2">
                            <a href="{{ route('shows.index') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                        </div>
                        <div class="col-10">
                            <div class="btn-group float-right">
                                <a href="#editModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>
                                <a href="#uploadBgModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-image"></i> &nbsp; Main</a>
                                <a href="#uploadHeaderModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-photo-video"></i> &nbsp; Header</a>
                                <a href="#deleteModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="lead">Status:  @if($show->is_active === 0) <span class="badge badge-danger">Inactive</span> @else <span class="badge badge-success">Active</span> @endif</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-black-50">Front Description</p>
                        <p class="lead">{{ $show->front_description }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-black-50">Show Icon</p>
                        <div class="row">
                            <div class="col-10">
                                <div class="text-center bg-dark">
                                    <img src="{{ asset('images/shows/'.$show->icon) }}" alt="{{ $show->icon }}" class="my-3" width="50px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="col-12">
                    <p class="text-black-50">Description</p>
                    <p class="lead">{!! $show->description !!}</p>
                </div>
                <hr class="my-4">
                <div class="row">
                    <div class="col-6">
                        <p class="lead">Jocks on Board</p>
                    </div>
                    <div class="col-6">
                        <p class="lead">Timeslot</p>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row">
                    <div class="col-6 mb-5">
                        @forelse($jock_show as $jock_shows)
                            <div class="row">
                                <div class="col-sm-10 col-md-10 col-lg-10">
                                    <form action="{{ route('shows.remove.jock', $show->id) }}" method="POST">
                                        @csrf
                                        <p class="lead">{{ $jock_shows->first_name }} {{ $jock_shows->last_name }} &nbsp;
                                            <input type="text" name="jock_id" id="jock_id" value="{{ $jock_shows->jock_id }}" style="display: none;">
                                            <button type="submit" class="btn btn-outline-dark">Remove</button></p>
                                    </form>
                                </div>
                            </div>
                        @empty
                        @endforelse
                        <div>
                            <a href="#addJock" class="btn btn-outline-dark" data-toggle="modal">Add Jock</a>
                        </div>
                    </div>
                    <div class="col-6">
                        @foreach($timeslot as $timeslots)
                            <div class="row">
                                <div class="col-sm-12 col-md-3 col-lg-3">
                                    <span class="lead">{{ $timeslots->day }}</span>
                                </div>
                                <div class="col-sm-12 col-md-9 col-lg-9">
                                    <span class="lead">{{ date('h:i a',strtotime($timeslots->start)) }} to {{ date('h:i a', strtotime($timeslots->end)) }}</span>
                                </div>
                            </div>
                            <hr class="my-4">
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-2">
                        <p class="lead fa-pull-left">Images</p>
                        <a href="#addImage" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Image</a>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            @if($image->isNotEmpty())
                                <div class="owl-carousel">
                                    @foreach($image as $images)
                                        <div class="col-3">
                                            <div class="card mb-3" style="width: 15rem; height: 450px;">
                                                <img src="{{ url('images/shows/'.$images->file) }}" class="card-img-top" alt="{{ $images->file }}">
                                                <div class="card-body">
                                                    <div class="card-text lead">
                                                        <br>
                                                        {{ Str::limit($images->name, $limit = 30, $end = '...') }}
                                                        <br>
                                                        {{ date('m-d-Y', strtotime($images->created_at)) }}
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="btn-group fa-pull-right">
                                                        <a href="#infoModal-{{ $images->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-info-circle"></i></a>
                                                        <a href="#deleteImage-{{ $images->id }}" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="text-center lead">
                                        No Photos
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}
    <div id="addJock" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">Add Jock</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('shows.add.jock', $show->id) }}">
                        @csrf
                        <div class="form-group">
                            <label class="label" for="jock_id"></label>
                            <select id="jock_id" name="jock_id" class="custom-select">
                                <option value selected>--</option>
                                <option value selected>--</option>
                                @forelse($jock as $employees)
                                    @foreach($employees->Jock as $jocks)
                                        <option value="{{ $jocks->id }}">{{ $jocks->name }}</option>
                                    @endforeach
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <br><br>
                        <button type="submit" class="btn btn-outline-dark">Add Jock</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">Edit {{ $show->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" action="{{ route('shows.update', $show->id) }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label class="label" for="title">Show Title</label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ $show->title }}">
                        </div>
                        <div class="form-group">
                            <label class="label" for="front_description">Front Description</label>
                            <input type="text" id="front_description" name="front_description" class="form-control" value="{{ $show->front_description }}">
                        </div>
                        <div class="form-group">
                            <label class="label" for="description">Description</label>
                            <textarea id="content" name="description" class="form-control" style="margin-top: 0; margin-bottom: 0; height: 200px;">{!! $show->description !!}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label" for="is_special">Specials</label>
                                    <select id="is_special" name="is_special" class="custom-select">
                                        @if($show->is_special === 1)
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                            <option value="2">Weekend Show</option>
                                        @endif
                                        @if($show->is_special === 0)
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                            <option value="2">Weekend Show</option>
                                        @endif
                                        @if($show->is_special === 2)
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                            <option value="2" selected>Weekend Show</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label" for="is_active">Active</label>
                                    <select id="is_active" name="is_active" class="custom-select">
                                        @if($show->is_active === 1)
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        @endif
                                        @if($show->is_active === 0)
                                                <option value="1">Yes</option>
                                                <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="label" for="header">Icon</label>
                                    <div id="header" class="custom-file">
                                        <label class="custom-file-label" for="icon">Show Icon</label>
                                        <input type="file" id="icon" name="image" class="custom-file-input">
                                    </div>
                                </div>
                            </div>
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

    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">Delete Show?</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span class="lead text-danger">Delete the Show {{ $show->title }}?</span>
                    <br>
                    <form method="post" action="{{ route('shows.destroy', $show->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark" value="delete">Yes</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="addImage" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="h5">Add Featured Show Image</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div><form method="post" action="{{ route('photos.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="text" id="show_id" name="show_id" value="{{ $show->id }}" style="display: none;">
                        <div class="form-group">
                            <label class="label" for="name">Image Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Image Name">
                        </div>
                        <div class="custom-file mb-3">
                            <input type="file" id="show_image" name="image" class="custom-file-input">
                            <label for="show_image" class="custom-file-label">Image</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group float-right">
                            <button type="submit" class="btn btn-outline-dark fa-pull-right">Save</button>
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="uploadBgModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Update Main Image
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="uploadBackgroundImageForm" method="POST" action="{{ route('shows.update.background.image', $show->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-file">
                                    <input type="file" name="image" id="background_image" class="custom-file-input">
                                    <div class="custom-file-label">Main image of the show.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-dark"><i class="fas fa-save"></i>  Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="uploadHeaderModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Update Header Image
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="uploadHeaderImageForm" method="POST" action="{{ route('shows.update.header.image', $show->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-file">
                                    <input type="file" name="image" id="header_image" class="custom-file-input">
                                    <div class="custom-file-label">Header image of the show.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-dark"><i class="fas fa-save"></i>  Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($show->Image as $images)
        <div id="deleteImage-{{ $images->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="h5">Delete Image?</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post" action="{{ route('photos.destroy', $images->id) }}">
                        <div class="modal-body">
                            <span class="lead text-danger">Delete Image {{ $images->name }}?</span>
                                @csrf
                                @method('DELETE')
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right">Yes</button>
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($show->Image as $images)
        <div id="infoModal-{{ $images->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="h5">More Information</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('photos.update', $images->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row justify-content-center">
                                <div class="col-4">
                                    <img src="{{ asset('images/shows/'. $images->file) }}" class="img-thumbnail" alt="{{ $images->name }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name">Image Name:</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $images->name }}" placeholder="Name">
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" id="file" name="file" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="file">Insert file here</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    {{-- END MODAL --}}
@endsection
