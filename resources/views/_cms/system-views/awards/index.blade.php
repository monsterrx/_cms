@extends('layouts.base')

@section('content')
    <div class="container">
        <div class="mt-md-4 mt-lg-4 mt-sm-0 mb-5">
            <div class="display-4 mb-3">
                Awards
            </div>

            <div class="row">
                <div class="col-md-12">
                    @include('_cms.system-views._feedbacks.success')
                    @include('_cms.system-views._feedbacks.error')
                </div>
            </div>

            @if(Auth()->user()->Employee->Designation->level === '1')
                <div class="row my-4">
                    <div class="col-md-12 col-sm-12 col-12 col-lg-12">
                        <a href="#new-award" data-toggle="modal" class="btn btn-outline-dark fa-pull-right">New Award</a>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover" id="awardsTable">
                                <thead>
                                <tr>
                                    <th>Date Created</th>
                                    <th>Show/Jock</th>
                                    <th>Award</th>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($awards as $award)
                                    <tr>
                                        <td>{{ $award->created_at }}</td>
                                        <td>
                                            @if($award->jock_id)
                                                {{ $award->Jock->name }}
                                            @elseif($award->show_id)
                                                {{ $award->Show->title }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($award->name === 'kbp')
                                                KBP Golden Dove Award
                                            @elseif($award->name === 'cmma')
                                                Catholic Mass Media Award
                                            @endif
                                        </td>
                                        <td>{{ $award->title }}</td>
                                        <td>{{ $award->year }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="#update-award" id="update-award-toggler" data-id="{{ $award->id }}" data-toggle="modal" class="btn btn-outline-dark">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <a href="#delete-award" id="delete-award-toggler" data-id="{{ $award->id }}" data-toggle="modal" class="btn btn-outline-dark">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="alert alert-danger text-center">
                                                No Awards Found
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
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="new-award" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Award</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('awards.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="label">Award Name</label>
                                    <select id="name" name="name" class="custom-select">
                                        <option value="kbp">KBP Golden Dove Award</option>
                                        <option value="cmma">Catholic Mass Media Award</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label" for="select_awardee">Awardee</label>
                                    <select id="select_awardee" name="select_awardee" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        <option value="jock">Jock</option>
                                        <option value="show">Show</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="awardee" name="awardee" class="custom-select" hidden>
                                        <option value="" disabled selected>--</option>
                                        @forelse($awards as $award)
                                            <option value="{{ $award->awardee_id }}">{{ $award->awardee }}</option>
                                        @empty
                                            <option value="" disabled selected>No Data Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title" class="label">Title</label>
                                    <input type="text" id="title" name="title" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label" for="year">Year</label>
                                    <input type="text" id="year" name="year" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="special" class="label">Special Award</label>
                                    <select id="special" name="is_special" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="label">Award Description</label>
                                    <textarea id="description" name="description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark"><i class="fas fa-save"></i>  Save</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-dark">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-award" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Award</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateAwardForm" action="" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="update_award" class="label">Award Name</label>
                                    <select id="update_award" name="name" class="custom-select">
                                        <option value="kbp">KBP Golden Dove Award</option>
                                        <option value="cmma">Catholic Mass Media Award</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label" for="update-awardee-type">Awardee</label>
                                    <select id="update-awardee-type" name="select_awardee" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        <option value="jock">Jock</option>
                                        <option value="show">Show</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="update-awardee" name="awardee" class="custom-select">
                                        <option value disabled selected>--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="update-title" class="label">Title</label>
                                    <input type="text" id="update-title" name="title" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="label" for="update-year">Year</label>
                                    <input type="text" id="update-year" name="year" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="update-special" class="label">Special Award</label>
                                    <select id="update-special" name="is_special" class="custom-select">
                                        <option value="" disabled selected>--</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="update-description" class="label">Award Description</label>
                                    <textarea id="update-description" name="description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark"><i class="fas fa-save"></i>  Save</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-dark">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-award" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Award</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteAwardForm" action="" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <div id="delete-award-body" class="lead h5 text-center">
                            Loading ...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group fa-pull-right">
                            <button type="submit" class="btn btn-outline-dark">Yes</button>
                            <button type="button" data-dismiss="modal" class="btn btn-outline-dark">No</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
