@extends('layouts.base')

@section('content')
    <div class="container-fluid">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">{{ $jock->name }}</div>
            <div class="my-3">
                @include('_cms.system-views._feedbacks.success')
                @include('_cms.system-views._feedbacks.error')
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row justify-content-center text-center">
                        <div class="col-12 col-sm-12 col-md">
                            <div class="card-img">
                                <img src="{{ $jock->main_image }}" class="img-thumbnail" alt="{{ $jock->main_image }}">
                            </div>
                            <div class="card-body">
                                <div class="card-text">Main Picture</div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md">
                            <div class="card-img">
                                <img src="{{ $jock->profile_image }}" class="img-thumbnail" alt="{{ $jock->profile_image }}">
                            </div>
                            <div class="card-body">
                                <div class="card-text">Profile Picture</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <form action="{{ route('jocks.update', $jock->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-12">
                            <hr class="my-4">
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="d-block d-md-none d-lg-none d-xl-none">
                                <div class="row justify-content-center">
                                    <div class="col">
                                        <div class="btn-group">
                                            <a href="#update-main" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-photo-video"></i></a>
                                            <a href="#update-profile" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-plus-circle"></i></a>
                                            <a href="{{ route('users.header', $jock->id) }}" class="btn btn-outline-dark"><i class="fa fa-image"></i></a>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group float-right">
                                            <button type="submit" class="btn btn-outline-dark"><i class="fas fa-check-circle"></i></button>
                                            <a href="{{ route('jocks.index') }}" class="btn btn-outline-dark"><i class="fas fa-times-circle"></i></a>
                                            <a href="{{ route('jocks.destroy', $jock->id) }}" class="btn btn-outline-danger" onclick="event.preventDefault(); document.getElementById('delete-jock').submit(); "><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none d-md-block d-lg-block d-xl-block">
                                <div class="row justify-content-between">
                                    <div class="col">
                                        <div class="btn-group">
                                            <a href="#update-main" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-photo-video"></i>  Main Photo</a>
                                            <a href="#update-profile" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-plus-circle"></i>  Profile Picture</a>
                                            <a href="{{ route('users.header', $jock->id) }}" class="btn btn-outline-dark"><i class="fa fa-image"></i>  Cover Photo</a>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="btn-group float-right">
                                            <button type="submit" class="btn btn-outline-dark"><i class="fas fa-check-circle"></i>  Update</button>
                                            <a href="{{ route('jocks.index') }}" class="btn btn-outline-dark"><i class="fas fa-times-circle"></i>  Back</a>
                                            <a href="{{ route('jocks.destroy', $jock->id) }}" class="btn btn-outline-danger" onclick="event.preventDefault(); document.getElementById('delete-jock').submit(); "><i class="fas fa-trash-alt"></i>  Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="lead">Status:  @if($jock->is_active === 0) <span class="badge badge-danger">Inactive</span> @else <span class="badge badge-success">Active</span> @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employee_id" class="label">Employee Name</label>
                                        <select id="employee_id" name="employee_id" class="custom-select">
                                            <option value="{{ $jock->Employee->id }}" selected>{{ $jock->Employee->first_name }} {{ $jock->Employee->last_name }}</option>
                                            @foreach($employees as $employee)
                                                <?php try { ?>
                                                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                                <?php } catch (ErrorException $e) { ?>
                                                <option value="{{ $employee->id }}">Deleted Data</option>
                                                <?php } ?>
                                            @endforeach
                                        </select>
                                        <label for="Designation" class="label">Designation</label>
                                        <input type="text" id="designation" name="designation" class="form-control" value="{{ $jock->Employee->Designation->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name" class="label">Jock Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $jock->name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="moniker" class="label">Moniker</label>
                                        <input type="text" id="moniker" name="moniker" class="form-control" value="{{ $jock->moniker }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="active" class="label">Active</label>
                                        <select id="active" name="is_active" class="form-control">
                                            @if($jock->active === '1')
                                                <option value="1" selected>Yes</option>
                                                <option value="0">No</option>
                                            @elseif($jock->active === '0')
                                                <option value="1">Yes</option>
                                                <option value="0" selected>No</option>
                                            @else
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-5">
                                    <div class="form-group">
                                        <label for="content" class="label">Description</label>
                                        <textarea id="content" name="description" class="form-control" maxlength="250">{{ $jock->description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="btn-group float-right">
                                        <a href="#add-jock-link-modal" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Social</a>
                                        <a href="#add-jock-fact-modal" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Fact</a>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <table id="jockLinksTable" class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Link</th>
                                                    <th>Website</th>
                                                    <th>Options</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="my-4"></div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <table id="jockFactsTable" class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Fact</th>
                                                    <th>Options</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="float-right">
                                        <a href="#add-jock-image-modal" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Jock Image</a>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <table id="jockImagesTable" class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Date Uploaded</th>
                                                    <th>Image File Name</th>
                                                    <th>Options</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <form id="delete-jock" method="POST" action="{{ route('jocks.destroy', $jock->id) }}" style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            <!-- Modals -->
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
            <div class="modal fade" id="update-main" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Main Photo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="profilePicForm" method="POST" action="{{ route('users.main.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 my-3">
                                        <input type="text" id="jock_id" name="jock_id" value="{{ $jock->id }}" style="display: none;">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="croppingMainImage" class="cropper img-fluid"></div>
                                            </div>
                                        </div>
                                        <input type="text" id="mainImageName" name="mainImageName" style="display: none;" />
                                        <div class="my-2"></div>
                                        <div class="custom-file" id="customMain">
                                            <input type="file" id="jockMainImage" name="image" class="custom-file-input" accept="image/*">
                                            <label id="imageLabel" for="image" class="custom-file-label">Click here</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="fa-pull-right">
                                            <button id="cropMainButton" type="button" class="btn btn-outline-dark" hidden>Crop</button>
                                            <button id="saveMainButton" type="submit" class="btn btn-outline-dark" hidden>Save</button>
                                            <button id="cancelMainButton" type="button" class="btn btn-outline-dark" data-role="none" hidden>Cancel</button>
                                            <button id="doneMainButton" type="button" class="btn btn-outline-dark" data-dismiss="modal">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete-jock-fact-modal" tabindex="-1" role="dialog" aria-labelledby="delete-jock-fact-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Remove Jock Fact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="remove_jock_fact_form" action="" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" id="remove_jock_fact_id" name="id" value="">
                            <div class="modal-body">
                                <div id="jock-remove-fact-text" class="text-center">
                                    Undefined
                                </div>
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
            <div class="modal fade" id="view-jock-fact-modal" tabindex="-1" role="dialog" aria-labelledby="view-jock-fact-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Jock Fact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_jock_fact_form" action="" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" id="view_jock_fact_id" name="jock_id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="update_content" class="lead">Fact</label>
                                    <input type="text" id="update_content" name="content" class="form-control" placeholder="Fact About this Jock">
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
            <div class="modal fade" id="add-jock-fact-modal" tabindex="-1" role="dialog" aria-labelledby="add-jock-fact-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Jock Fact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('facts.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jock_id" value="{{ $jock->id }}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="content" class="lead">Fact</label>
                                            <input type="text" id="content" name="content" class="form-control" placeholder="Fact About this Jock">
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

            <div class="modal fade" id="delete-jock-link-modal" tabindex="-1" role="dialog" aria-labelledby="delete-jock-link-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Remove Jock Socials</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="delete_jock_link_form" action="" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" id="remove_jock_link_id" name="id" value="">
                            <div class="modal-body">
                                <div id="jock-remove-link-text" class="text-center">
                                    Undefined
                                </div>
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
            <div class="modal fade" id="view-jock-link-modal" tabindex="-1" role="dialog" aria-labelledby="view-jock-link-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Jock Link</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_jock_link_form" action="" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" id="view_jock_link_id" name="jock_id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="update_website" class="lead">Website</label>
                                    <select id="update_website" name="website" class="custom-select">
                                        <option value selected>--</option>
                                        <?php $websites = array(0 => 'Facebook', 1 => 'Twitter', 2 => 'Instagram', 3 => 'Youtube', 4 => 'Tumblr', 5 => 'Spotify', 6 => 'Tiktok', 7 => 'Other'); ?>
                                        @foreach($websites as $website)
                                            <option value="{{ $website }}">{{ $website }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="update_url" class="lead">Link</label>
                                    <input type="text" id="update_url" name="url" class="form-control" placeholder="Social Media Link">
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
            <div class="modal fade" id="add-jock-link-modal" tabindex="-1" role="dialog" aria-labelledby="add-jock-link-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Jock Link</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('socials.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jock_id" value="{{ $jock->id }}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="website" class="lead">Website</label>
                                            <select id="website" name="website" class="custom-select">
                                                <option value selected>--</option>
                                                <?php $websites = array(0 => 'Facebook', 1 => 'Twitter', 2 => 'Instagram', 3 => 'Youtube', 4 => 'Tumblr', 5 => 'Spotify', 6 => 'Tiktok', 7 => 'Other'); ?>
                                                @foreach($websites as $website)
                                                    <option value="{{ $website }}">{{ $website }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="url" class="lead">Link</label>
                                            <input type="text" id="url" name="url" class="form-control" placeholder="Social Media Link">
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

            <div class="modal fade" id="delete-jock-image-modal" tabindex="-1" role="dialog" aria-labelledby="delete-jock-image-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Remove Jock Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="delete_jock_image_form" action="" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" id="remove_jock_image_id" name="id" value="">
                            <div class="modal-body">
                                <div id="jock-remove-image-text" class="text-center">
                                    Undefined
                                </div>
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
            <div class="modal fade" id="view-jock-image-modal" tabindex="-1" role="dialog" aria-labelledby="view-jock-image-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Jock Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_jock_image_form" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" id="view_jock_image_id" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="text-center">
                                        <img id="view_image" src="{{ asset('images/jocks/default.png') }}" class="img-thumbnail" alt="Undefined">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image_name">Jock Image Name</label>
                                    <input type="text" id="image_name" name="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <label for="main_image">Jock Image Name</label>
                                        <input type="file" id="main_image" name="file" class="custom-file-input" accept="image/*" alt>
                                        <div id="custom-file-label" class="custom-file-label">Jock Image</div>
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
            <div class="modal fade" id="add-jock-image-modal" tabindex="-1" role="dialog" aria-labelledby="add-jock-image-modal"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Jock Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="jock_id" value="{{ $jock->id }}">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="add_image_name">Image Name</label>
                                    <input type="text" id="add_image_name" name="name" class="form-control" placeholder="Image Name">
                                </div>
                                <div class="form-group">
                                    <label for="add_jock_image">Jock Image</label>
                                    <div class="custom-file">
                                        <input type="file" id="custom_file" name="image" class="custom-file-input" accept="image/*">
                                        <div class="custom-file-label">Click Here to Select</div>
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


        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            let url = '{{ route('jocks.show', $jock->id) }}';

            $('#jockImagesTable').DataTable({
                ajax: {
                    url: url,
                    dataSrc: 'images',
                    data: {
                        jock_info: "jock_info"
                    },
                },
                columns: [
                    { data: 'date_created' },
                    { data: 'name' },
                    { data: 'options' }
                ],
                order: [
                    [ 0, 'desc' ]
                ]
            });

            $('#jockLinksTable').DataTable({
                ajax: {
                    url: url,
                    dataSrc: 'links',
                    data: {
                        jock_info: "jock_info"
                    },
                },
                columns: [
                    { data: 'website' },
                    { data: 'url' },
                    { data: 'options' ,
                    }
                ],
                order: [
                    [ 0, 'desc' ]
                ],
            });

            $('#jockFactsTable').DataTable({
                ajax: {
                    url: url,
                    dataSrc: 'facts',
                    data: {
                        jock_info: "jock_info"
                    },
                },
                columns: [
                    { data: 'content' },
                    { data: 'options' ,
                    }
                ],
                order: [
                    [ 0, 'desc' ]
                ],
            });
        });
    </script>
@endsection
