@extends('layouts.main')

@section('employee.nav')
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mobileNav" aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mobileNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            @foreach($show as $shows)
                @if($shows->id === 4) {{-- The Daily Survey --}}
                <li class="nav-item">
                    <a href="{{ route('charts.daily') }}" class="nav-link">Daily Survey Top 5</a>
                </li>
                @endif
            @endforeach
            <li class="nav-item">
                <a class="nav-link" href="{{ route('survey.votes') }}">Hit List Votes</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->Employee->FirstName }} {{ Auth::user()->Employee->LastName }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="{{ route('jocks.profile', $jock_id) }}">Profile</a>
                    <a href="#reportBug" class="dropdown-item" data-toggle="modal">Report a Bug</a>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign Out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
@endsection

@section('employee.content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-5">
                    <img id="headerPic" src="{{ url('images/jocks/'.$jock->background_image) }}" class="card-img img-fluid" alt="{{ Auth()->user()->Employee->Jock->first()->background_image }}">
                    <div class="card-body-modified">
                        <div class="card-profile">
                            <div class="row">
                                <div class="col-4">
                                    <img id="profilePic" src="{{ url('images/jocks/'.$jock->profile_img) }}" class="profilePic img-fluid rounded-circle img-thumbnail" width="300px" alt="{{ Auth()->user()->Employee->Jock->first()->profile_img }}">
                                </div>
                                <div class="col"></div>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profileName lead">
                                    <div class="d-block d-sm-block d-lg-none d-md-block d-xl-none">
                                        <div class="h5" id="name">{{ Auth()->user()->Employee->FirstName }} {{ Auth()->user()->Employee->LastName }}</div>
                                    </div>
                                    <div class="d-none d-lg-block d-sm-none d-xl-block d-md-none">
                                        <div class="h3" id="name1">{{ Auth()->user()->Employee->FirstName }} {{ Auth()->user()->Employee->LastName }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="h3 fa-pull-left">Jock Details</div>
                                        <div class="fa-pull-right d-none d-sm-none d-md-block d-lg-block d-xl-block">
                                            <a href="#update-employee" class="btn btn-outline-dark" data-toggle="modal">Employee Info&nbsp;&nbsp;<i class="fas fa-user"></i></a>
                                            <a href="#update-jock" class="btn btn-outline-dark" data-toggle="modal">Jock Info&nbsp;&nbsp;<i class="fas fa-pen"></i></a>
                                            <a href="#update-profile" class="btn btn-outline-dark" data-toggle="modal">Profile&nbsp;&nbsp;<i class="fas fa-user-circle"></i></a>
                                            <a href="#update-header" class="btn btn-outline-dark" data-toggle="modal">Cover&nbsp;&nbsp;<i class="fas fa-image"></i></a>
                                            <a href="#passwordModal" class="btn btn-outline-dark" data-toggle="modal">Password&nbsp;&nbsp;<i class="fas fa-user-lock"></i></a>
                                        </div>
                                        <div class="fa-pull-right d-sm-block d-md-none d-lg-none d-xl-none">
                                            <a href="#update-employee" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-user"></i></a>
                                            <a href="#update-jock" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-pen"></i></a>
                                            <a href="#update-profile" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-user-circle"></i></a>
                                            <a href="#update-header" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-image"></i></a>
                                            <a href="#passwordModal" class="btn btn-outline-dark" data-toggle="modal"><i class="fas fa-user-lock"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-block d-sm-block d-lg-none d-md-block d-xl-none">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="lead">
                                                                <div class="h5">Jock Name:</div> {{ Auth()->user()->Employee->Jock->first()->jock_name }}
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="lead">
                                                                <div class="h5">Contact:</div> <p id="contactNumber">{{ Auth()->user()->Employee->ContactNo }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="lead">
                                                                <div class="h5">Moniker:</div> @if(Auth()->user()->Employee->Jock->first()->moniker === null || Auth()->user()->Employee->Jock->first()->moniker === '') No Moniker @else Auth()->user()->Employee->Jock->first()->moniker @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="lead">
                                                                <div class="h5">Email:</div> <p id="email">{{ Auth()->user()->email }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="lead">
                                                Description:
                                                <p class="h5 text-center">
                                                    {!! Auth()->user()->Employee->Jock->first()->description ? Auth()->user()->Employee->Jock->first()->description : 'Get the audience to know who you are by writing your description' !!}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-none d-lg-block d-sm-none d-xl-block d-md-none">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="lead ml-5">
                                                                <div class="h5">Jock Name:</div> {{ Auth()->user()->Employee->Jock->first()->jock_name }}
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="lead ml-5">
                                                                <div class="h5">Contact:</div> <p id="contactNumber1">{{ Auth()->user()->Employee->ContactNo }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="lead ml-5">
                                                                <div class="h5">Moniker:</div> @if(Auth()->user()->Employee->Jock->first()->moniker === null || Auth()->user()->Employee->Jock->first()->moniker === '') No Moniker @else Auth()->user()->Employee->Jock->first()->moniker @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="lead ml-5">
                                                                <div class="h5">Email:</div> <p id="email">{{ Auth()->user()->email }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="lead ml-5">
                                                Description:
                                                <p class="h5 text-center">
                                                    {!! Auth()->user()->Employee->Jock->first()->description ? Auth()->user()->Employee->Jock->first()->description : 'Get the audience to know who you are by writing your description' !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="my-4"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="h3 fa-pull-left">Social Media</p>
                                        <a href="#add-soc" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mx-1 my-1">
                                        @if($link->isNotEmpty())
                                            <ul class="list-group">
                                                @foreach($link as $links)
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-md-6">{{ $links->url }}</div>
                                                            <div class="col-md-6">
                                                                <form id="deleteLink" action="{{ route('jocks.delete.link', $links->id) }}" method="post">
                                                                    <button type="submit" value="delete" class="btn btn-outline-dark fa-pull-right">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <button href="#edit-{{ $links->id }}" data-toggle="modal" class="mx-2 btn btn-outline-dark fa-pull-right">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="alert alert-info text-center alert mt-1">
                                                <p class="h6 m-0">Click the plus button to add the links where people can find you!</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="h3 fa-pull-left">Facts</p>
                                        <a href="#add-facts" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                @if($fact->isNotEmpty())
                                    <div class="list-group">
                                        @foreach($fact as $facts)
                                            <a href="#edit-fact-{{ $facts->id }}" data-toggle="modal" class="list-group-item list-group-item-action zoom">{{ str_limit($facts->facts, $limit = 15, $end = '...') }}</a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info text-center alert mt-1">
                                        <p class="h6 m-0">Click the plus button to add facts about you.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="h3 fa-pull-left">Photos</p>
                                        <a href="#add-photo" class="btn btn-outline-dark fa-pull-right" data-toggle="modal"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if($image->isNotEmpty())
                                            <div class="owl-carousel">
                                                @foreach($image as $images)
                                                    <div class="card my-3 mx-5 zoom" data-toggle="modal" data-target="#edit-photo-{{ $images->id }}">
                                                        <img src="{{ asset('images/jocks/'.$images->main_image) }}" width="50px" class="card-img" alt="{{ $images->image_name }}">
                                                        <div class="card-body">
                                                            <div class="hidden-xs hidden-sm">
                                                                <div class="card-text">{{ $images->image_name }}</div>
                                                            </div>
                                                            <div class="hidden-md hidden-lg">
                                                                <div class="card-text">{{ str_limit($images->image_name, $limit = '1', $end = '...') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info text-center alert mt-1">
                                                <p class="h6 m-0">Click the plus button to add your Photos!</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-employee" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Your Contact Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="employee-form" action="{{ route('employees.update', $employee->id) }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="FirstName">First Name</label>
                                <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First Name" required value="{{ $employee->FirstName }}">
                            </div>

                            <div class="col-md-6">
                                <label for="LastName">Last Name</label>
                                <input type="text" id="LastName" name="LastName" class="form-control" placeholder="Last Name" required value="{{ $employee->LastName }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Address">Address</label>
                            <input type="text" id="Address" name="Address" class="form-control" placeholder="Address" required value="{{ $employee->Address }}">
                        </div>

                        <div class="form-group">
                            <label for="Birthday">Birthday</label>
                            <input type="date" id="Birthday" name="Birthday" class="form-control" required value="{{ $employee->Birthday }}">
                        </div>

                        <div class="form-group">
                            <label for="ContactNo">Contact Number</label>
                            <input type="text" id="ContactNo" name="ContactNo" class="form-control" placeholder="Contact Number" required value="{{ $employee->ContactNo }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="submit-button" class="btn btn-outline-dark">Update</button>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-jock" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('jocks.update', Auth::user()->Employee->Jock->first()->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="jock_name" class="lead">Jock Name</label>
                                    <input type="text" id="jock_name" name="jock_name" class="form-control" value="{{ Auth::user()->Employee->Jock->first()->jock_name }}" placeholder="Jock Name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="moniker" class="lead">Moniker</label>
                                    <input type="text" id="moniker" name="moniker" class="form-control" value="{{ Auth::user()->Employee->Jock->first()->moniker }}" placeholder="Moniker">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content" class="lead">Description</label>
                                    <input type="text" id="content" name="description" class="form-control" value="{{ Auth::user()->Employee->Jock->first()->description }}" placeholder="Description">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group fa-pull-right">
                                    <button type="submit" class="btn btn-outline-dark">Save</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-outline-dark">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-profile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile Photo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="profilePicForm" method="POST" action="{{ route('users.image.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 my-3">
                                <input type="text" id="jock_id" name="jock_id" value="{{ $jock->id }}" style="display: none;">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="croppingImage" class="cropper img-fluid"></div>
                                    </div>
                                </div>
                                <input type="text" id="imageName" name="imageName" style="display: none;" />
                                <div class="my-2"></div>
                                <div class="custom-file" id="custom">
                                    <input type="file" id="jockImage" name="image" class="custom-file-input" accept="image/*">
                                    <label id="imageLabel" for="image" class="custom-file-label">Click here</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button id="cropButton" type="button" class="btn btn-outline-dark" hidden>Crop</button>
                                    <button id="saveButton" type="submit" class="btn btn-outline-dark" hidden>Save</button>
                                    <button id="cancelButton" type="button" class="btn btn-outline-dark" data-role="none" hidden>Cancel</button>
                                    <button id="doneButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">Done</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-header" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Cover Photo</h5>
                </div>
                <form id="headerForm" method="POST" action="{{ route('users.header.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 my-3">
                                <input type="text" id="jock_id" name="jock_id" value="{{ $jock->id }}" style="display: none;">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="croppingHeaderImage" class="cropperHeader img-fluid"></div>
                                    </div>
                                </div>
                                <input type="text" id="headerImageName" name="headerImageName" style="display: none;" />
                                <div class="custom-file" id="headerCustom">
                                    <input type="file" id="header" name="header" class="custom-file-input" accept="image/*">
                                    <label for="header" class="custom-file-label">Click Here</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <button id="cropHeaderButton" type="button" class="btn btn-outline-dark" hidden>Crop</button>
                                    <button id="saveHeaderButton" type="submit" class="btn btn-outline-dark" hidden>Save</button>
                                    <button id="cancelHeaderButton" type="button" class="btn btn-outline-dark" data-role="none" hidden>Cancel</button>
                                    <button id="doneHeaderButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">Done</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add-soc" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title lead">Add Social Media Links</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('jocks.add.link') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="jock_id" id="jock_id" value="{{ $jock_id }}">
                                <div class="form-group">
                                    <label for="website" class="lead">Website</label>
                                    <select id="website" name="website" class="custom-select">
                                        <option value selected>--</option>
                                        <?php $website = array(0 => 'Facebook', 1 => 'Twitter', 2 => 'Instagram', 3 => 'Youtube', 4 => 'Tumblr', 5 => 'Spotify', 6 => 'Tiktok', 7 => 'Other'); ?>
                                        @foreach($website as $websites)
                                            <option value="{{ $websites }}">{{ $websites }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="lead">Link</label>
                                    <input type="text" id="url" name="url" class="form-control" placeholder="Social Media Link">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="add-facts" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title lead">Add Facts</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('facts.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="jock_id" id="jock_id" value="{{ $jock_id }}">
                                <div class="form-group">
                                    <label for="facts" class="lead">Fact</label>
                                    <input type="text" id="facts" name="facts" class="form-control" placeholder="Facts About You">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark fa-pull-right">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="add-photo" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="h3">Add Photos</div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('jocks.store.image') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $jock_id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image_name" class="lead">Name</label>
                                    <input type="text" id="image_name" name="image_name" class="form-control" placeholder="Image Name">
                                </div>
                                <div class="form-group">
                                    <label for="formLabel" class="lead">Image</label>
                                    <div class="custom-file" id="formLabel">
                                        <input type="file" id="image" name="image" class="custom-file-input">
                                        <label for="image" class="custom-file-label">Image max size is 2MB</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-outline-dark"><i class="fas fa-save"></i>  Save</button>
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

    @foreach($image as $images)
        <div id="edit-photo-{{ $images->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="h3">Update Photo</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <img src="{{ asset('images/jocks/'.$images->main_image) }}" class="card-img" alt="{{ $images->image_name }}">
                        </div>
                        <form method="post" action="{{ route('jocks.update.image') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="text" name="id" value="{{ $images->id }}" style="display:none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="image_name" class="lead">Name</label>
                                        <input type="text" id="image_name" name="image_name" class="form-control" value="{{ $images->image_name }}" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="custom" class="lead">Image</label>
                                    <div class="custom-file" id="custom">
                                        <input type="file" id="image" name="image" class="custom-file-input">
                                        <label for="image" class="custom-file-label">{{ $images->main_image }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="my-4"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-outline-dark fa-pull-right">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="{{ route('jocks.remove.image') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $images->id }}">
                            <button type="submit" class="btn btn-outline-dark">Delete</button>
                        </form>
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($link as $links)
        <div id="edit-{{ $links->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="h3">Edit {{ $links->website }} Link</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ route('jocks.update.link', $links->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="text" id="jock_id" name="jock_id" value="{{ $jock_id }}" hidden>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="url" class="lead">Edit {{ $links->website }} Link</label>
                                        <input type="text" id="url" name="url" class="form-control" value="{{ $links->url }}" placeholder="Link">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-outline-dark fa-pull-right"><i class="fas fa-file-import"></i>  Update</button>
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
    @endforeach

    @foreach($fact as $facts)
        <div id="edit-fact-{{ $facts->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title lead">Edit Fact</div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ action('FactController@update', $facts->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="jock_id" id="jock_id" value="{{ $jock_id }}">
                                    <div class="form-group">
                                        <label for="facts" class="lead">Fact</label>
                                        <input type="text" id="facts" name="facts" class="form-control" value="{{ $facts->facts }}" placeholder="Facts about you">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-outline-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-dark" onclick="event.preventDefault(); document.getElementById('deleteForm').submit();">Delete</button>

                        <form id="deleteForm" action="{{ action('FactController@destroy', $facts->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop
