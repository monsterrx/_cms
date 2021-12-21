@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Jocks
            </div>

            <div class="row col-md-12">
                @include('_cms.system-views._feedbacks.success')
                @include('_cms.system-views._feedbacks.error')
            </div>

            <div class="my-4"></div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="jocksTable" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Employee Name</th>
                                    <th>Jock Name</th>
                                    <th>Moniker</th>
                                    <th>Location</th>
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

    <!-- Modal -->
    <div class="modal fade" id="view_jock_modal" tabindex="-1" role="dialog" aria-labelledby="view_jock_modal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="view_jock_modal_title" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="update_jock_form" action="" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="update_employee_id" name="employee_id">
                    <input type="hidden" id="update_designation_id" name="designation_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <p class="text-center">Jock Main Image</p>
                                        <div class="card-img">
                                            <img id="update_main_image" src="" class="img-thumbnail" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <p class="text-center">Jock Profile Image</p>
                                        <div class="card-img">
                                            <img id="update_profile_image" src="" class="img-thumbnail" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <p class="text-center">Jock Background Image</p>
                                        <div class="card-img">
                                            <img id="update_background_image" src="" class="img-thumbnail" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="col-md-12">
                                <div class="fa-pull-right">
                                    <div class="d-block d-sm-none">
                                        <div class="btn-group">
                                            <a href="#update-profile" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-plus-circle"></i></a>
                                            <a href="#update-header" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-image"></i></a>
                                        </div>
                                    </div>
                                    <div class="d-none d-md-block d-lg-block d-xl-block">
                                        <div class="btn-group">
                                            <a href="#update-profile" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-plus-circle"></i>  Profile Picture</a>
                                            <a href="#update-header" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-image"></i>  Cover Photo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br><br>
                            <div class="col-md-12 mb-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="update_employee_name" class="label">Employee Name</label>
                                            <input type="text" id="update_employee_name" class="form-control" readonly disabled>
                                            <label for="update_designation" class="label">Designation</label>
                                            <input type="text" id="update_designation" class="form-control" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="update_name" class="label">Jock Name</label>
                                            <input type="text" id="update_name" name="name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="update_moniker" class="label">Moniker</label>
                                            <input type="text" id="update_moniker" name="moniker" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="update_is_active" class="label">Active</label>
                                            <select id="update_is_active" name="is_active" class="form-control">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <div class="form-group">
                                            <label for="update-content" class="label">Description</label>
                                            <textarea id="update-content" name="description" class="form-control" style="height: 160px;"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="btn-group fa-pull-right">
                                            <a href="#add-jock-link-modal" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Social</a>
                                            <a href="#add-jock-fact-modal" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Fact</a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
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

                                    <div class="col-md-6">
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

                                    <div class="col-md-12">
                                        <div class="fa-pull-right mb-3">
                                            <a href="#add-jock-image-modal" data-toggle="modal" class="btn btn-outline-dark"><i class="fas fa-plus"></i>  Add Jock Image</a>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-5">
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
    <div class="modal fade" id="delete_jock_modal" tabindex="-1" role="dialog" aria-labelledby="delete_jock_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="delete_jock_modal_title" class="modal-title">Undefined</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete_jock_form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete_jock_modal_body_text" class="text-center">
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
                                <input type="hidden" id="profile_pic_jock_id" name="jock_id">
                                <div class="row">
                                    <div class="col-12">
                                        <div id="croppingImage" class="cropper img-fluid"></div>
                                    </div>
                                </div>
                                <input type="text" id="name" name="name" style="display: none;" />
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
                <form id="headerForm" method="POST" action="{{ route('users.header.update') }}">
                    @csrf
                    <div class="modal-body">
                        <div id="row" class="row">
                            <div class="col-md-12 my-3">
                                <input type="hidden" id="header_pic_jock_id" name="jock_id">
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
                    {{ method_field('DELETE') }}

                    <input type="hidden" id="remove_fact_jock_id" name="id" value="">
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
                    <input type="hidden" id="view_fact_jock_id" name="jock_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="update_facts" class="lead">Fact</label>
                            <input type="text" id="update_facts" name="content" class="form-control" placeholder="Fact About this Jock">
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
                    <input type="hidden" id="new_fact_jock_id" name="jock_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="facts" class="lead">Fact</label>
                                    <input type="text" id="facts" name="content" class="form-control" placeholder="Fact About this Jock">
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
                    {{ method_field('DELETE') }}

                    <input type="hidden" id="remove_link_jock_id" name="id" value="">
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

                    <input type="hidden" id="view_link_jock_id" name="jock_id" value="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="update_website" class="lead">Website</label>
                            <select id="update_website" name="website" class="custom-select">
                                <option value selected>--</option>
                                <?php $websites = array(0 => 'Facebook', 1 => 'Twitter', 2 => 'Instagram', 3 => 'YouTube', 4 => 'Tumblr', 5 => 'Other'); ?>
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
                <form action="{{ route('jocks.add.link') }}" method="POST">
                    @csrf
                    <input type="hidden" id="new_link_jock_id" name="jock_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="website" class="lead">Website</label>
                                    <select id="website" name="website" class="custom-select">
                                        <option value selected>--</option>
                                        <?php $website = array(0 => 'Facebook', 1 => 'Twitter', 2 => 'Instagram', 3 => 'YouTube', 4 => 'Tumblr', 5 => 'Other'); ?>
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
                <form action="{{ route('jocks.remove.image') }}" method="POST">
                    @csrf
                    {{ method_field('DELETE') }}

                    <input type="hidden" id="remove_image_jock_id" name="id" value="">
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
                <form action="{{ route('jocks.update.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" id="view_image_jock_id" name="id" value="">
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
                                <input type="file" id="main_image" name="image" class="custom-file-input" accept="image/*" alt>
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
                <form action="{{ route('jocks.store.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="new_image_jock_id" name="jock_id">
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
@endsection
