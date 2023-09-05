@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4">
                Edit Radio1 Jock
            </div>
            <div class="row">
                <div class="col-12">
                    @include('_cms.system-views._feedbacks.error')
                    @include('_cms.system-views._feedbacks.success')
                </div>
            </div>
            <div class="row">
                <div class="col-12 my-4">
                    <div class="btn-group">
                        <a href="{{ route('radioOne.jocks') }}" class="btn btn-outline-dark"><i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{ route('radioOne.jocks.update', $student->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="lead text-dark">ID: <span class="text-danger">{{ $student->id }}</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <img src="{{ $student->image }}" width="400px" alt="{{ $student->image }}" class="my-3"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name" class="lead">First Name</label>
                                                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $student->first_name }}" placeholder="{{ $student->first_name }}" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name" class="lead">Last Name</label>
                                                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $student->last_name }}" placeholder="{{ $student->last_name }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nickname" class="lead">Nickname</label>
                                                    <input type="text" id="nickname" name="nickname" class="form-control" value="{{ $student->nickname }}" placeholder="{{ $student->nickname }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="school_id" class="lead">School</label>
                                                    <select id="school_id" name="school_id" class="custom-select">
                                                        <option value="{{ $student->School->id }}">{{ $student->School->name }}</option>
                                                        @forelse($schools as $school)
                                                            <option value="{{ $school->id }}">{{ $school->name }}</option>
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
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="content" class="lead">Student Bio</label>
                                                    <textarea id="content" name="description" class="form-control" maxlength="250">{!! $student->description !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-4">
                                    <div class="col-md-12">
                                        <a href="#add-r1-social" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-plus"></i>  Add Socials</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="radio1SocialsTable" class="table table-hover">
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
                                <div class="row my-4">
                                    <div class="col-md-12">
                                        <a href="#add-r1-photo" data-toggle="modal" class="btn btn-outline-dark"><i class="fa fa-plus"></i>  Add Photos</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="radio1PhotosTable" class="table table-hover">
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
                            <div class="card-footer">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-outline-dark" value="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="add-r1-social" tabindex="-1" role="dialog" aria-labelledby="add-r1-social"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Student Jock Social</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('socials.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="student_jock_id" value="{{ $student->id }}">
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

            <div class="modal fade" id="view-r1-social" tabindex="-1" role="dialog" aria-labelledby="view-r1-social"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Jock Link</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_r1_jock_link_form" action="" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" id="view_radio1_jock_link_id" name="student_jock_id" value="{{ $student->id }}">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="update_website" class="lead">Website</label>
                                    <select id="update_website" name="website" class="custom-select">
                                        <option value selected>--</option>
                                        @php
                                            $websites = array(0 => 'Facebook', 1 => 'Twitter', 2 => 'Instagram', 3 => 'Youtube', 4 => 'Tumblr', 5 => 'Spotify', 6 => 'Tiktok', 7 => 'Other');
                                        @endphp
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

            <div class="modal fade" id="delete-r1-social" tabindex="-1" role="dialog" aria-labelledby="delete-r1-social"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Remove Student Jock Social Media</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="delete_r1_jock_link_form" action="" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" id="remove_jock_link_id" name="id" value="{{ $student->id }}">
                            <div class="modal-body">
                                <div id="jock-radio1-remove-link-text" class="text-center">
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

            <div class="modal fade" id="add-r1-photo" tabindex="-1" role="dialog" aria-labelledby="add-r1-photo"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Student Jock Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="student_jock_id" value="{{ $student->id }}">
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

            <div class="modal fade" id="delete-r1-photo" tabindex="-1" role="dialog" aria-labelledby="delete-r1-photo"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Remove Jock Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="delete_r1_image_form" action="" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" id="remove_r1_image_id" name="id" value="">
                            <div class="modal-body">
                                <div id="r1-remove-image-text" class="text-center">
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

            <div class="modal fade" id="view-r1-photo" tabindex="-1" role="dialog" aria-labelledby="view-r1-photo"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Student Jock Image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_r1_image_form" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" id="view_r1_image_id" name="id" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="text-center">
                                        <img id="r1_view_image" src="{{ asset('images/jocks/default.png') }}" class="img-thumbnail" alt="Undefined">
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
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        let radio1socials = $('#radio1SocialsTable').DataTable({
            ajax: {
                url: '{{ route('radioOne.jocks.show', $student->id) }}?type=socials',
                dataSrc: 'link',
            },
            columns: [
                {
                    data: "url",
                },
                {
                    data: "website",
                },
                {
                    data: "options"
                }
            ],
        });
        let radio1photos = $('#radio1PhotosTable').DataTable({
            ajax: {
                url: '{{ route('radioOne.jocks.show', $student->id) }}?type=photos',
                dataSrc: 'image',
            },
            columns: [
                {
                    data: "date_created",
                },
                {
                    data: "name",
                },
                {
                    data: "options"
                }
            ],
        });
    </script>
@endsection
